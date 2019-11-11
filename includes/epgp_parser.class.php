<?php
/*	Project:	EQdkp-Plus
 *	Package:	EPGPimport Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if(!defined('EQDKP_INC')) {
	header('HTTP/1.0 Not Found');
	exit;
}

if(!class_exists('epgp_parser')) {
	class epgp_parser extends gen_class {
		public function __construct() {
		}
		
		public function parse($strLog, $intEventID, $intItempoolID){
			if($this->config->get('dkp_easymode')){
				$intItempoolID = $this->pdh->get('event', 'def_itempool', array($intEventID));
			}
			
			if ($objLog = json_decode($strLog)){
				$strTime = $this->time->date("Y-m-d H:i", intval($objLog->timestamp));
				$intTime = intval($objLog->timestamp);
				$arrRaidList = $this->pdh->get('raid', 'raididsindateinterval', array($intTime, PHP_INT_MAX));
				foreach($arrRaidList as $raidid){
					$strNote = $this->pdh->get('raid', 'note', array($raidid));
					if (strpos($strNote, "EPGP-Snapshot") === 0){
						return false;
					}
				}
				
				//Build itemList
				$blnRaidItemList = false;
				$arrItemMembernameServernameList = array();
				$arrItemMemberList = array();
				$arrItemList = array();
				//$objLootItem: 0:timestamp, 1:Charname, 2:item:113834:0:0:0:0:0:0:0:100:0:3:0
				foreach($objLog->loot as $objLootItem){
					//Try to check if item was imported, but how? When editing the raid, the item date will be the one from the raid
					//Get all raids between first item and now, and try to find the item with same name, value and buyer
					if ($blnRaidItemList === false){
						$intTmpTime = (int)$objLootItem[0];
						$arrRaidList = $this->pdh->get('raid', 'raididsindateinterval', array($intTmpTime, PHP_INT_MAX));
						foreach($arrRaidList as $raidid){
							$arrRaidItems = $this->pdh->get('item', 'itemsofraid', array($raidid));
							foreach($arrRaidItems as $itemid){
								$intBuyerID = $this->pdh->get('item', 'buyer', array($itemid));
								$char_server	= $this->pdh->get('member', 'profile_field', array($intBuyerID, 'servername'));
								$servername		= ($char_server != '') ? $char_server : $this->config->get('servername');
								
								$strBuyerName = $this->pdh->get('item', 'buyer_name', array($itemid)).'-'.unsanitize($servername);
								
								$arrItemMembernameServernameList[$strBuyerName][] = array(
										'gameid'	=> $this->pdh->get('item', 'game_itemid', array($itemid)),
										'value'		=> (float)$this->pdh->get('item', 'value', array($itemid)),
								);
							}
						}
						$blnRaidItemList = true;
					}

					$strBuyerName = $objLootItem[1];
					if(strpos($strBuyerName, '-') === false){
						$strBuyerName = $strBuyerName.'-'.unsanitize($this->config->get('servername'));
					}
					
					$strGameID = (is_numeric((string)$objLootItem[2])) ? intval($objLootItem[2]) : str_replace('item:', '', (string)$objLootItem[2]);
					$floatValue = (float)$objLootItem[3];
					
					if (isset($arrItemMembernameServernameList[$strBuyerName])){
						$blnNotThere = true;
						foreach($arrItemMembernameServernameList[$strBuyerName] as $value){

							if ($strGameID == $value['gameid'] && $floatValue == $value['value']){
								$blnNotThere = false;
								break;
							}
						}

						if ($blnNotThere){
							$arrItemList[] = array(
								'gameid' => $strGameID,
								'value'	 => $floatValue,
								'buyer'	 => $strBuyerName
							);
							$arrItemMemberList[$strBuyerName] += $floatValue; 
						}
						
					} else {
						$arrItemList[] = array(
							'gameid' => $strGameID,
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
					
					list($membername, $servername) = explode('-', $objRosterItem[0]);
					$servername = preg_replace_callback(
							"/([^A-Z\'\"\-; ])([A-Z])/",
							function($m) { return $m[1].' '.$m[2]; },
							$servername
					);
					
					$strMembername = trim($membername);

					$strServername = (isset($servername) && strlen($servername)) ? trim($servername) : $this->config->get('servername'); 
					
					$strFullMembername = $strMembername.'-'.unsanitize($strServername);
					
					$floatEP = (float)$objRosterItem[1];
					$floatGP = (float)$objRosterItem[2];
										
					//Get MemberID, if none, create member
					$intMemberID = $this->pdh->get('member', 'id', array(sanitize($strMembername), array('servername' => sanitize($strServername))));
					
					if (!$intMemberID){
						//create new Member
						$data = array(
							'name' 		=> $strMembername,
							'level'		=> 0,
							'race'		=> 0,
							'class'		=> 0,
							'rankid'	=> $this->pdh->get('rank', 'default', array()),
							'servername'=> $strServername,
						);
						$intMemberID = $this->pdh->put('member', 'addorupdate_member', array(0, $data));
						$this->pdh->process_hook_queue();
						
						$floatCurrentEP = 0;
						$floatCurrentGP = 0;
					} else {
						$arrMDKPools = $this->pdh->get('event', 'multidkppools', array($intEventID));
						$intMultidkpID = $arrMDKPools[0];
						$floatCurrentEP = $this->pdh->get('epgp', 'ep', array($intMemberID, $intMultidkpID, false, false));
						$floatCurrentGP = $this->pdh->get('epgp', 'gpwithbp', array($intMemberID, $intMultidkpID, false, false));
					}
					
					$floatAdjustement = $floatEP - $floatCurrentEP;
					if ($floatAdjustement != 0){
						//create adjustment
						$arrAdjustment[$floatAdjustement][] = array(
							'value' => $floatAdjustement,
							'member'=> $intMemberID,
							'reason'=> 'EP, Snapshot '.$strTime,
						);
					}
					
					$floatGPItem = $floatGP - $floatCurrentGP - ((isset($arrItemMemberList[$strFullMembername])) ? $arrItemMemberList[$strFullMembername] : 0);

					//create dummy GP Item
					$arrItem[(float)$floatGPItem][] = array(
						'value'		=> (float)$floatGPItem,
						'name'		=> 'GP, Snapshot '.$strTime,
						'gameid'	=> 0,
						'member'	=> $intMemberID,
					);

					$arrMember[] = $intMemberID;
				}

				//Create raid with value 0
				$raid_upd = $this->pdh->put('raid', 'add_raid', array($intTime, $arrMember, $intEventID, 'EPGP-Snapshot '.$strTime, 0));

				if ($raid_upd){
					//Add Adjustments
					foreach ($arrAdjustment as $val => $a){
						if($val == 0) continue;
						$arrMembers = array();
						foreach($a as $adj){
							$arrMembers[] = $adj['member'];	
						}
						$adj_upd[] = $this->pdh->put('adjustment', 'add_adjustment', array($adj['value'], $adj['reason'], $arrMembers, $intEventID, $raid_upd, $intTime));
					}
					
					foreach ($arrItem as $val => $a){
						if($val == 0) continue;
						$arrMembers = array();
						foreach($a as $item){
							$arrMembers[] = $item['member'];	
						}
						
						$item_upd[] = $this->pdh->put('item', 'add_item', array($item['name'], $arrMembers, $raid_upd, $item['gameid'], $item['value'], $intItempoolID, $intTime));
					}
					//Add Items
					foreach ($arrItemList as $item){
						if ($item['value'] == 0) continue;
						
						$arrMembername = explode("-", $item['buyer']);
						$strBuyerName = trim($arrMembername[0]);
						$strBuyerServername = (isset($arrMembername[1]) && strlen($arrMembername[1])) ? trim($arrMembername[1]) : $this->config->get('servername');
						
						$intMemberID = $this->pdh->get('member', 'id', array($strBuyerName, array('servername' => $strBuyerServername)));
						if ($intMemberID) {
							$item_upd[] = $this->pdh->put('item', 'add_item', array('', $intMemberID, $raid_upd, $item['gameid'], $item['value'], $intItempoolID, $intTime));
						} else {

						}
					}
					
					$this->pdh->process_hook_queue();
					return $raid_upd;
				}
			} else {
				//Is it a CVS for CEPGP?
				/* Charname, Class, Rank, EP, GP, EP/GP
				 * Sneakums,Warlock,Member,0,1,0
					Spellcaster,Mage,Initiate,0,1,0
					Sprunky,Rogue,Member,16,29,0.55
				 */
				if(strpos($strLog, ',') !== false){
					$arrMember = array();
					$arrAdjustment = array();
					
					$strTime = $this->time->date("Y-m-d H:i", $this->time->time);
					
					$arrLines = explode("\r\n", trim($strLog));
					foreach($arrLines as $strLine){
						$arrMemberItem = explode(',', trim($strLine));
						
						list($membername, $servername) = explode('-', $arrMemberItem[0]);
						$servername = preg_replace_callback(
								"/([^A-Z\'\"\-; ])([A-Z])/",
								function($m) { return $m[1].' '.$m[2]; },
								$servername
								);
						
						$strMembername = trim($membername);
						
						$strServername = (isset($servername) && strlen($servername)) ? trim($servername) : $this->config->get('servername');
						
						$strFullMembername = $strMembername.'-'.unsanitize($strServername);
						
						$floatEP = (float)$arrMemberItem[3];
						$floatGP = (float)$arrMemberItem[4];
						$strRankname = $arrMemberItem[2];
						$strClassname = $arrMemberItem[1];
						$intClassID = $this->resolveClassname($strClassname);
						
						//Get MemberID, if none, create member
						$intMemberID = $this->pdh->get('member', 'id', array(sanitize($strMembername), array('servername' => sanitize($strServername))));
						
						if (!$intMemberID){
							//create new Member
							$data = array(
									'name' 		=> $strMembername,
									'level'		=> 0,
									'race'		=> 0,
									'class'		=> $intClassID,
									'rankid'	=> $this->pdh->get('rank', 'byname', array($strRankname)),
									'servername'=> $strServername,
							);
							$intMemberID = $this->pdh->put('member', 'addorupdate_member', array(0, $data));
							$this->pdh->process_hook_queue();
							
							$floatCurrentEP = 0;
							$floatCurrentGP = 0;
						} else {
							$arrMDKPools = $this->pdh->get('event', 'multidkppools', array($intEventID));
							$intMultidkpID = $arrMDKPools[0];
							$floatCurrentEP = $this->pdh->get('epgp', 'ep', array($intMemberID, $intMultidkpID, false, false));
							$floatCurrentGP = $this->pdh->get('epgp', 'gpwithbp', array($intMemberID, $intMultidkpID, false, false));
						}
						
						$floatAdjustement = $floatEP - $floatCurrentEP;
						if ($floatAdjustement != 0){
							//create adjustment
							$arrAdjustment[(float)$floatAdjustement][] = array(
									'value' => (float)$floatAdjustement,
									'member'=> $intMemberID,
									'reason'=> 'EP, Snapshot '.$strTime,
							);
						}
						
						$floatGPItem = $floatGP - $floatCurrentGP;
						
						//create dummy GP Item
						$arrItem[(float)$floatGPItem][] = array(
								'value'		=> (float)$floatGPItem,
								'name'		=> 'GP, Snapshot '.$strTime,
								'gameid'	=> 0,
								'member'	=> $intMemberID,
						);
						
						$arrMember[] = $intMemberID;
					}

					//Create raid with value 0
					$raid_upd = $this->pdh->put('raid', 'add_raid', array($this->time->time, $arrMember, $intEventID, 'EPGP-Snapshot '.$strTime, 0));
					$adj_upd = $item_upd = array();
					
					if ($raid_upd){
						//Add Adjustments
						foreach ($arrAdjustment as $val => $a){
							if($val == 0) continue;
							$arrMembers = array();
							foreach($a as $adj){
								$arrMembers[] = $adj['member'];
							}
							$adj_upd[] = $this->pdh->put('adjustment', 'add_adjustment', array($adj['value'], $adj['reason'], $arrMembers, $intEventID, $raid_upd, $this->time->time));
						}
						
						foreach ($arrItem as $val => $a){
							if ($val == 0) continue;
							$arrMembers = array();
							foreach($a as $item){
								$arrMembers[] = $item['member'];
							}
							$item_upd[] = $this->pdh->put('item', 'add_item', array($item['name'], $arrMembers, $raid_upd, $item['gameid'], $item['value'], $intItempoolID, $this->time->time));
						}
												
						$this->pdh->process_hook_queue();
						return $raid_upd;
					}
					
					
				}
				
			}
			
			return false;
		}

	
		private function resolveClassname($strClassname){
			if(strtolower($strClassname) == strtolower('DEATHKNIGHT')){
				$strClassname = "Death Knight";
			}
			if(strtolower($strClassname) == strtolower('DEMONHUNTER')){
				$strClassname = "Demon Hunter";
			}
			
			$intClassID = $this->game->get_id('classes', $strClassname);
			return $intClassID;
		}
		
	}
}
?>