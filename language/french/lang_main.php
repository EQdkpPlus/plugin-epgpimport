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
 
if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: French	
//Created by EQdkp Plus Translation Tool on  2013-01-27 08:28
//File: plugins/epgpimport/language/french/lang_main.php
//Source-Language: english

$lang = array( 
	"epgpimport" => 'Importer EPGP',
	"epgpimport_import" => 'Importer le log EPGP',
	"epgpimport_short_desc" => 'Importateur EPGP',
	"epgpimport_long_desc" => 'Importer votre log EPGP depuis l\'addon en jeu.',
	"epgpimport_error_nolog" => 'Aucun log EPGP fourni',
	"epgpimport_error_wrongformat" => 'Le log EPGP ne peut être importé. Il est peut-être endommagé, dans un mauvais format ou un log plus récent a déjà été importé.',
	"epgpimport_layoutwarning" => 'You don\'t use an EPGP-Layout. This can cause uncorrectly imports. Please go to the <a href="'.registry::get_const('root_path').'admin/manage_pagelayouts.php'.registry::get_const('SID').'">Pagelayout-Management</a> and select an EPGP-Layout.',
	"epgpimport_success" => 'Log EPGP importé avec succès',
	
);

?>