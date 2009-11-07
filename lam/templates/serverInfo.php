<?php
/*
$Id$

  This code is part of LDAP Account Manager (http://www.ldap-account-manager.org/)
  Copyright (C) 2009  Roland Gruber

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/**
* Provides a list of LAM tests.
*
* @author Roland Gruber
* @package tools
*/

/** security functions */
include_once("../lib/security.inc");
/** access to configuration options */
include_once("../lib/config.inc");

// start session
startSecureSession();

setlanguage();

$namingContexts = '';
$configcontext = '';
$supportedldapversion = '';
$supportedsaslmechanisms = '';
$subschemasubentry = '';

$result = @ldap_read($_SESSION['ldap']->server(), '', 'objectclass=*', array('+', '*'));
if ($result) {
	$info = @ldap_get_entries($_SESSION['ldap']->server(), $result);
	if ($info) {
		$info = $info[0];
		foreach ($info as $key => $value) {
			if (is_array($info[$key]) && isset($info[$key]['count'])) {
				unset($info[$key]['count']);
			}
		}
		if (isset($info['namingcontexts'])) {
			$namingContexts = implode(', ', $info['namingcontexts']);
		}
		if (isset($info['configcontext'])) {
			$configcontext = $info['configcontext'][0];
		}
		if (isset($info['supportedldapversion'])) {
			$supportedldapversion = $info['supportedldapversion'][0];
		}
		if (isset($info['supportedsaslmechanisms'])) {
			$supportedsaslmechanisms = implode(', ', $info['supportedsaslmechanisms']);
		}
		if (isset($info['subschemasubentry'])) {
			$subschemasubentry = $info['subschemasubentry'][0];
		}
	}
}

echo $_SESSION['header'];


echo "<title></title>\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../style/layout.css\">\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../style/type_user.css\">\n";
echo "</head>";

echo "<body>\n";

echo "<h1 align=\"center\">" . _("Server information") . "</h1>\n";

echo "<table class=\"userlist\" rules=\"none\">\n";

echo "<tr class=\"userlist\"><td style=\"padding:10px;\"><b>" . _("Managed suffix(es)") . "</b>&nbsp;&nbsp;</td>";
echo "<td style=\"padding:10px;\">" . $namingContexts . "</td></tr>";

echo "<tr class=\"userlist\"><td style=\"padding:10px;\"><b>" . _("LDAP version") . "</b>&nbsp;&nbsp;</td>";
echo "<td style=\"padding:10px;\">" . $supportedldapversion . "</td></tr>";

echo "<tr class=\"userlist\"><td style=\"padding:10px;\"><b>" . _("Config suffix") . "</b>&nbsp;&nbsp;</td>";
echo "<td style=\"padding:10px;\">" . $configcontext . "</td></tr>";

echo "<tr class=\"userlist\"><td style=\"padding:10px;\"><b>" . _("Schema suffix") . "</b>&nbsp;&nbsp;</td>";
echo "<td style=\"padding:10px;\">" . $subschemasubentry . "</td></tr>";

echo "<tr class=\"userlist\"><td style=\"padding:10px;\"><b>" . _("SASL mechanisms") . "</b>&nbsp;&nbsp;</td>";
echo "<td style=\"padding:10px;\">" . $supportedsaslmechanisms . "</td></tr>";

echo "</table>\n";



echo "</body>\n";
echo "</html>\n";

?>