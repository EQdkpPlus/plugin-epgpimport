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
		'epgpimport_import' => 'Import EPGP-Log',
		'epgpimport_short_desc' => 'EPGP-Importer',
		'epgpimport_long_desc' => 'Import your EPGP-Logs from the ingame-Addon',
		'epgpimport_error_nolog' => 'No EPGP-Log given',
		'epgpimport_error_wrongformat' => 'EPGP-Log could not be imported, maybe it is damaged, has a wrong format or a newer one has already been imported.',
		'epgpimport_layoutwarning' => 'You don\'t use an EPGP-Layout. This can cause uncorrectly imports. Please go to the <a href="'.registry::get_const('root_path').'admin/manage_pagelayouts.php'.registry::get_const('SID').'">Pagelayout-Management</a> and select an EPGP-Layout.',
		'epgpimport_success' => 'EPGP-Log successfully imported.',
		'epgpimport_error_more_mdkp4event' => 'EPGP-Log could not be imported, because the selected event belongs to more than one MultiDKP-Pool.',
	);
	

?>