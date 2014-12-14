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

	$lang = array(
		'epgpimport' => 'EPGP-Import',
		'epgpimport_import' => 'EPGP-Log importieren',
		'epgpimport_short_desc' => 'EPGP-Importer',
		'epgpimport_long_desc' => 'Importiere EPGP-Logs aus deinem Spiel',
		'epgpimport_error_nolog' => 'Es wurde kein EPGP-Log angegeben.',
		'epgpimport_error_wrongformat' => 'Der EPGP-Log konnte nicht importiert werden, da er fehlerhaft ist, ein falsches Format besitzt oder bereits ein neurer EPGP-Log importiert wurde.',
		'epgpimport_layoutwarning' => 'Du hast als Layout kein EPGP-Layout ausgewählt. Dadurch kann es zu fehlerhaften Importen kommen. Bitte gehe in die <a href="'.registry::get_const('root_path').'admin/manage_pagelayouts.php'.registry::get_const('SID').'">Layoutverwaltung</a> und wähle ein EPGP-Layout aus.',
		'epgpimport_success' => 'Der EPGP-Log wurde erfolgreich importiert.',
		'epgpimport_error_more_mdkp4event' => 'Der EPGP-Log konnte nicht importiert werden, da das ausgewählte Event mehr als einem MultiDKP-Pool zugeordnet ist.',
	);
	

?>