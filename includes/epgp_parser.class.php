<?php
 /*
 * Project:     EQdkp-Plus Raidlogimport
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2009
 * Date:        $Date: 2012-11-11 16:50:58 +0100 (So, 11. Nov 2012) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2008-2009 hoofy_leon
 * @link        http://eqdkp-plus.com
 * @package     raidlogimport
 * @version     $Rev: 12431 $
 *
 * $Id: epgp_parser.class.php 12431 2012-11-11 15:50:58Z wallenium $
 */

if(!defined('EQDKP_INC')) {
	header('HTTP/1.0 Not Found');
	exit;
}

if(!class_exists('epgp_parser')) {
	class epgp_parser extends gen_class {
		public static $shortcuts = array('in', 'pdh', 'user', 'config', 'time');

		public function __construct() {
		}
		
		public function parse($strLog, $intEventID, $intItempoolID){
			
			if ($objLog = json_decode($strLog)){
				$strTime = $this->time->date("Y-m-d H:i", intval($objLog->timestamp));
				$intTime = intval($objLog->timestamp);
				$arrRaidList = $this->pdh->get('raid', 'raididsindateinterval', array($intTime, 9999999999));
				foreach($arrRaidList as $raidid){
					$strNote = $this->pdh->get('raid', 'note', array($raidid));
					if (strpos($strNote, "EPGP-Snapshot") ===0){
						return false;
					}
				}
				
			
				//Build itemList
				$blnRaidItemList = false;
				$arrItemMembernameList = array();
				$arrItemMemberList = array();
				$arrItemList = array();
				foreach($objLog->loot as $objLootItem){
					//Try to check if item was imported, but how? When editing the raid, the item date will be the one from the raid
					//Get all raids between first item and now, and try to find the item with same name, value and buyer
					if ($blnRaidItemList === false){
						$intTmpTime = $objLootItem[0];
						$arrRaidList = $this->pdh->get('raid', 'raididsindateinterval', array($intTmpTime, 9999999999));
						foreach($arrRaidList as $raidid){
							$arrRaidItems = $this->pdh->get('item', 'itemsofraid', array($raidid));
							foreach($arrRaidItems as $itemid){
								$strBuyerName = $this->pdh->get('item', 'buyer_name', array($itemid));
								$arrItemMembernameList[$strBuyerName][] = array(
									'gameid'	=> (int)$this->pdh->get('item', 'game_itemid', array($itemid)),
									'value'		=> (float)$this->pdh->get('item', 'value', array($itemid)),
								);
							}
						}
						$blnRaidItemList = true;
					}

					$strBuyerName = $objLootItem[1];
					if (isset($arrItemMembernameList[$strBuyerName])){
						$blnNotThere = true;
						foreach($arrItemMembernameList[$strBuyerName] as $value){
							$intGameID = intval($objLootItem[2]);
							$floatValue = (float)$objLootItem[3];

							if ($intGameID == $value['gameid'] && $floatValue == $value['value']){
								$blnNotThere = false;
								break;
							}
						}

						if ($blnNotThere){
							$arrItemList[] = array(
								'gameid' => intval($objLootItem[2]),
								'value'	 => (float)$objLootItem[3],
								'buyer'	 => $strBuyerName
							);
							$arrItemMemberList[$strBuyerName] += $floatValue; 
						}
						
					} else {
						$intGameID = intval($objLootItem[2]);
						$floatValue = (float)$objLootItem[3];
						$arrItemList[] = array(
							'gameid' => $intGameID,
							'value'	 => $floatValue,
							'buyer'	 => $strBuyerName
						);
						$arrItemMemberList[$strBuyerName] += $floatValue;
					}
				
				}

				//The members
				$arrMember = array();
				$arrAdjustment = array();
				foreach($objLog->roster as $objRosterItem){
					$strMembername = $objRosterItem[0];
					$floatEP = (float)$objRosterItem[1];
					$floatGP = (float)$objRosterItem[2];
										
					//Get MemberID, if none, create member
					$intMemberID = $this->pdh->get('member', 'id', array($strMembername));
					if (!$intMemberID){
						//create new Member
						$data = array(
							'name' 		=> $strMembername,
							'lvl' 		=> 0,
							'raceid'	=> 0,
							'classid'	=> 0,
							'rankid'	=> $this->pdh->get('rank', 'default', array()),
						);
						$intMemberID = $this->pdh->put('member', 'addorupdate_member', array(0, $data));
						$this->pdh->process_hook_queue();
						
						$floatCurrentEP = 0;
						$floatCurrentGP = 0;
					} else {
						$arrMDKPools = $this->pdh->get('event', 'multidkppools', array($intEventID));
						$intMultidkpID = $arrMDKPools[0];
						$floatCurrentEP = $this->pdh->get('epgp', 'ep', array($intMemberID, $intMultidkpID, false, false));
						$floatCurrentGP = $this->pdh->get('epgp', 'gp', array($intMemberID, $intMultidkpID, false, false));
					}
					
					$floatAdjustement = $floatEP - $floatCurrentEP;
					if ($floatAdjustement != 0){
						//create adjustment
						$arrAdjustment[] = array(
							'value' => $floatAdjustement,
							'member'=> $intMemberID,
							'reason'=> 'EP, Snapshot '.$strTime,
						);
					}
					$floatGPItem = $floatGP - $floatCurrentGP - ((isset($arrItemMemberList[$strMembername])) ? $arrItemMemberList[$strMembername] : 0);

					//create dummy GP Item
					$arrItem[] = array(
						'value' => (float)$floatGPItem,
						'name' => 'GP, Snapshot '.$strTime,
						'gameid' => 0,
						'member' => $intMemberID,
					);

					$arrMember[] = $intMemberID;
				}
				//d($arrAdjustment);

				//Create raid with value 0
				$raid_upd = $this->pdh->put('raid', 'add_raid', array($intTime, $arrMember, $intEventID, 'EPGP-Snapshot '.$strTime, 0));

				if ($raid_upd){
					//Add Adjustments
					foreach ($arrAdjustment as $adj){
						if ($adj['value'] == 0) continue;
						$adj_upd[] = $this->pdh->put('adjustment', 'add_adjustment', array($adj['value'], $adj['reason'], $adj['member'], $intEventID, $raid_upd, $intTime));
					}
					$itempoolid = 1;
					foreach ($arrItem as $item){
						if ($item['value'] == 0) continue;
						$item_upd[] = $this->pdh->put('item', 'add_item', array($item['name'], $item['member'], $raid_upd, $item['gameid'], $item['value'], $intItempoolID, $intTime));
					}
					//Add Items
					foreach ($arrItemList as $item){
						if ($item['value'] == 0) continue;
						$intMemberID = $this->pdh->get('member', 'id', array($item['buyer']));
						if ($intMemberID) {
							$item_upd[] = $this->pdh->put('item', 'add_item', array('', $intMemberID, $raid_upd, $item['gameid'], $item['value'], $intItempoolID, $intTime));
						} else {
							/*
							//create new Member
							$data = array(
								'name' 		=> $item['buyer'],
								'lvl' 		=> 0,
								'raceid'	=> 0,
								'classid'	=> 0,
								'rankid'	=> $this->pdh->get('rank', 'default', array()),
							);
							$intMemberID = $this->pdh->put('member', 'addorupdate_member', array(0, $data));
							$this->pdh->process_hook_queue();
							if ($intMemberID) $item_upd[] = $this->pdh->put('item', 'add_item', array('', $intMemberID, $raid_upd, $item['gameid'], $item['value'], $intItempoolID, $intTime));
							*/
						}
					}
					
					$this->pdh->process_hook_queue();
					return $raid_upd;
				}
			}
			return false;
		}
	}
}

if(version_compare(PHP_VERSION, '5.3.0', '<')) {
	registry::add_const('short_epgp_parser', epgp_parser::$shortcuts);
}
?>