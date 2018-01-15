<?php

/* Licensed To: ThoughtExecution & 9sistemes
* Authored By: Rishi Raj Bansal
* Developed in: Jul-Aug-Sep 2016
 * ===========================================================================
 * This is FULLY owned and COPYRIGHTED by ThoughtExecution
 * This code may NOT be RESOLD or REDISTRUBUTED under any circumstances, and is only to be used with this application
 * Using the code from this application in another application is strictly PROHIBITED and not PERMISSIBLE
 * ===========================================================================
*/

/*
 * Before Updating any SQLs, verify the usages in all places in the code
 */

define("SQL_SELECT_LIST_ORGS_DETAILS", "SELECT * FROM orgs ORDER BY name");
define("SQL_SELECT_LIST_RPTTMPLS_DETAILS", "SELECT * FROM orgs_rpt_templates");
define("SQL_SELECT_RPT_QFILTERS", "SELECT * FROM rpts_qfilters");

define("SQL_SELECT_ORGS_BY_NAME", "SELECT * FROM orgs ORDER BY name");
define("SQL_SELECT_ADMIN_LOGIN_TIME", "SELECT * FROM superadmin");
define("SQL_SELECT_ORGS_RPT_STRUCTS_COUNT", "SELECT count(*) as count FROM orgs_rpt_structs");
define("SQL_SELECT_ORGS_RPT_TEMPLATE_COUNT", "SELECT count(*) as count FROM orgs_rpt_templates");
define("SQL_SELECT_ORGS_RPT_QFILTERS_COUNT", "SELECT count(*) as count FROM orgs_rpt_qfilters WHERE trim(qfilters) != ''");

define("SQL_SELECT_DYNAFIELDS_PRODCUTS", "SELECT * FROM prdts_fields");
define("SQL_SELECT_DYNAFIELDS_TASKS", "SELECT * FROM tsks_fields");
define("SQL_SELECT_DYNAFIELDS_WORKERS", "SELECT * FROM wrks_fields");
define("SQL_SELECT_DYNAFIELDS_CUSTOMERS", "SELECT * FROM cstmrs_fields");
define("SQL_SELECT_DYNAFIELDS_REPORTING", "SELECT * FROM rpts_fields");

define("SQL_SELECT_DYNAFIELDS_PRODUCTS_MAPPING", "SELECT * FROM prdts_fields WHERE idprdts_fields IN (P1)");
define("SQL_SELECT_DYNAFIELDS_TASKS_MAPPING", "SELECT * FROM tsks_fields WHERE idtsks_fields IN (P1)");
define("SQL_SELECT_DYNAFIELDS_WORKERS_MAPPING", "SELECT * FROM wrks_fields WHERE idwrks_fields IN (P1)");
define("SQL_SELECT_DYNAFIELDS_CUSTOMERS_MAPPING", "SELECT * FROM cstmrs_fields WHERE idcstmrs_fields IN (P1)");
define("SQL_SELECT_DYNAFIELDS_RPT_MAPPING", "SELECT * FROM rpts_fields WHERE idrpts_fields IN (P1)");

define("SQL_SELECT_ORGS_DETAILS", "SELECT * FROM orgs WHERE idorgs = P1");
define("SQL_SELECT_ORGS_PRODUCTS", "SELECT * FROM orgs_prdts WHERE idorgs = P1");
define("SQL_SELECT_ORGS_TASKS", "SELECT * FROM orgs_tsks WHERE idorgs = P1");
define("SQL_SELECT_ORGS_WORKERS", "SELECT * FROM orgs_wrks WHERE idorgs = P1");
define("SQL_SELECT_ORGS_CUSTOMERS", "SELECT * FROM orgs_cstmrs WHERE idorgs = P1");
define("SQL_SELECT_ORGS_RPT_STRUCT", "SELECT * FROM orgs_rpt_structs WHERE idorgs = P1");
define("SQL_SELECT_ORGS_LOGIN_TIME", "SELECT * FROM orgs WHERE idorgs = P1");

define("SQL_SELECT_ORGS_NAMECHECK", "SELECT * FROM orgs WHERE name = P1");

define("SQL_SELECT_XEDIT_DYNA_VALUES", "SELECT * FROM xedit_dyna_values WHERE dyna_type = P1");

define("SQL_SELECT_ORGS_TEMPLATE_DETAILS", "SELECT * FROM orgs_rpt_templates WHERE idorgs = P1");

define("SQL_SELECT_ORGS_RPT_STRUCTURE_DETAILS", "SELECT * FROM orgs_rpt_structs WHERE idorgs = P1");
define("SQL_SELECT_ORGS_RPT_QFILTERS", "SELECT * FROM orgs_rpt_qfilters WHERE idorgs = P1");

define("SQL_SELECT_SUPERADMIN", "SELECT * FROM superadmin WHERE username = P1 and password = P2");
define("SQL_SELECT_USER_LOGIN", "SELECT * FROM orgs WHERE name = P1 AND username = P2 and password = P3");

define("SQL_SELECT_USER_RPT_GRAPH_MONTHLY", "SELECT count(*) as count, Month(sub_datetime) as month FROM org_rpts_P1 WHERE sub_datetime >= CONCAT(YEAR(NOW()), '-01-01 00:00:00') AND sub_datetime <= CONCAT(YEAR(NOW()), '-12-31 00:00:00') GROUP BY MONTH(sub_datetime)");

define("SQL_SELECT_USER_ORGS_PRDS_ALL", "SELECT * FROM org_prds_P1");
define("SQL_SELECT_USER_ORGS_TSKS_ALL", "SELECT * FROM org_tsks_P1");
define("SQL_SELECT_USER_ORGS_WRKS_ALL", "SELECT * FROM org_wrks_P1");
define("SQL_SELECT_USER_ORGS_CSTMRS_ALL", "SELECT * FROM org_cstmrs_P1");
define("SQL_SELECT_USER_ORGS_RPTS_ALL", "SELECT * FROM org_rpts_P1 ORDER BY sub_datetime");
define("SQL_SELECT_USER_ORGS_PRDS", "SELECT * FROM org_prds_P1 WHERE idorg_prds = P2");
define("SQL_SELECT_USER_ORGS_TSKS", "SELECT * FROM org_tsks_P1 WHERE idorg_tsks = P2");
define("SQL_SELECT_USER_ORGS_WRKS", "SELECT * FROM org_wrks_P1 WHERE idorg_wrks = P2");
define("SQL_SELECT_USER_ORGS_CSTMRS", "SELECT * FROM org_cstmrs_P1 WHERE idorg_cstmrs = P2");
define("SQL_SELECT_USER_ORGS_RPTS", "SELECT * FROM org_rpts_P1 WHERE idorg_rpts = P2");
define("SQL_SELECT_USER_ORGS_RPTS_COUNT", "SELECT count(*) as count FROM org_rpts_P1");

define("SQL_SELECT_USER_RPT_QF_CLIENT_NAME_FIELDID", "SELECT * FROM cstmrs_fields WHERE html_name = P1");
define("SQL_SELECT_USER_RPT_QF_WORKER_NAME_FIELDID", "SELECT * FROM wrks_fields WHERE html_name = P1");
define("SQL_SELECT_USER_RPT_QF_PRODUCTSUSED_FIELDID", "SELECT * FROM rpts_fields WHERE html_name = P1");
define("SQL_SELECT_USER_RPT_QF_FIELDID_MANY", "SELECT * FROM rpts_fields WHERE html_name IN (P1)");
//define("SQL_SELECT_USER_RPT_QF_TCW", "SELECT * FROM org_rpts_P1 WHERE sub_datetime >= P2 AND sub_datetime <= P3 AND sub_by = P4 AND clientname = P5 order by sub_datetime");
define("SQL_SELECT_USER_RPT_QF_TCW", "SELECT * FROM org_rpts_P1 WHERE P_SC order by sub_datetime");
define("SQL_SELECT_USER_RPT_QF_CAL_W_HRS", "SELECT * FROM org_rpts_P1 WHERE P_SC order by sub_datetime");
//define("SQL_SELECT_USER_RPT_QF_TTL_PRD_QTY", "SELECT * FROM org_rpts_P1 WHERE sub_datetime >= P2 AND sub_datetime <= P3 AND sub_by = P4 AND clientname = P5 order by sub_datetime");
define("SQL_SELECT_USER_RPT_QF_TTL_PRD_QTY", "SELECT * FROM org_rpts_P1 WHERE P_SC order by sub_datetime");

define("SQL_SELECT_USER_GET_DYNAID", "SELECT * FROM P1 WHERE html_name = P2");



define("SQL_INSERT_ORGS", "INSERT INTO orgs (name, phone, email, username, password, activated, created_on) VALUES (P1, P2, P3, P4, P5, P6, P7)");

define("SQL_INSERT_ORGS_PRDTS", "INSERT INTO orgs_prdts (idorgs, dyna_fields_ids, dyna_fields_list_values_en, dyna_fields_list_values_es, created_on) VALUES (P1, P2, P3, P4, P5)");
define("SQL_INSERT_ORGS_TSKS", "INSERT INTO orgs_tsks (idorgs, dyna_fields_ids, dyna_fields_list_values_en, dyna_fields_list_values_es, created_on) VALUES (P1, P2, P3, P4, P5)");
define("SQL_INSERT_ORGS_WRKS", "INSERT INTO orgs_wrks (idorgs, dyna_fields_ids, dyna_fields_list_values_en, dyna_fields_list_values_es, created_on) VALUES (P1, P2, P3, P4, P5)");
define("SQL_INSERT_ORGS_CSTMRS", "INSERT INTO orgs_cstmrs (idorgs, dyna_fields_ids, dyna_fields_list_values_en, dyna_fields_list_values_es, created_on) VALUES (P1, P2, P3, P4, P5)");

define("SQL_INSERT_XEDIT_DYNA_VALUES", "INSERT INTO xedit_dyna_values (dyna_type, dyna_id, dyna_values) VALUES (P1, P2, P3)");

define("SQL_INSERT_ORGS_TEMPLATES", "INSERT INTO orgs_rpt_templates (idorgs, template_name, rawtemplate_id, created_on) VALUES (P1, P2, P3, P4)");

define("SQL_INSERT_ORGS_RPT_STRUCTURE", "INSERT INTO orgs_rpt_structs (idorgs, prds_dyna_fields, tsks_dyna_fields, wrks_dyna_fields, cstmrs_dyna_fields, rpts_dyna_fields, created_on) VALUES (P1, P2, P3, P4, P5, P6, P7)");
define("SQL_INSERT_ORGS_RPT_QFILTERS", "INSERT INTO orgs_rpt_qfilters (idorgs, qfilters, created_on) VALUES (P1, P2, P3)");

define("SQL_INSERT_USER_ORGS_PRDS", "INSERT INTO org_prds_P1 (field_id_values, created_on) VALUES (P2, P3)");
define("SQL_INSERT_USER_ORGS_TSKS", "INSERT INTO org_tsks_P1 (field_id_values, created_on) VALUES (P2, P3)");
define("SQL_INSERT_USER_ORGS_WRKS", "INSERT INTO org_wrks_P1 (field_id_values, created_on) VALUES (P2, P3)");
define("SQL_INSERT_USER_ORGS_CSTMRS", "INSERT INTO org_cstmrs_P1 (field_id_values, created_on) VALUES (P2, P3)");



define("SQL_UPDATE_ORGS", "UPDATE orgs SET name = P1, phone = P2, email = P3, username = P4, password = P5, last_updated = P6 WHERE idorgs = P7");
define("SQL_UPDATE_ORGS_ACTDEACTIVATE", "UPDATE orgs SET activated = P1, last_updated = P2 WHERE idorgs = P3");

define("SQL_UPDATE_ORGS_PRDTS", "UPDATE orgs_prdts SET dyna_fields_ids = P1, dyna_fields_list_values_en = P2, dyna_fields_list_values_es = P3,last_updated = P4 WHERE idorgs = P5");
define("SQL_UPDATE_ORGS_TSKS", "UPDATE orgs_tsks SET dyna_fields_ids = P1, dyna_fields_list_values_en = P2, dyna_fields_list_values_es = P3,last_updated = P4 WHERE idorgs = P5");
define("SQL_UPDATE_ORGS_WRKS", "UPDATE orgs_wrks SET dyna_fields_ids = P1, dyna_fields_list_values_en = P2, dyna_fields_list_values_es = P3,last_updated = P4 WHERE idorgs = P5");
define("SQL_UPDATE_ORGS_CSTMRS", "UPDATE orgs_cstmrs SET dyna_fields_ids = P1, dyna_fields_list_values_en = P2, dyna_fields_list_values_es = P3,last_updated = P4 WHERE idorgs = P5");

define("SQL_UPDATE_ORGS_TEMPLATES", "UPDATE orgs_rpt_templates SET template_name = P1, rawtemplate_id = P2, last_updated = P3 WHERE idorgs = P4");

define("SQL_UPDATE_ORGS_RPT_STRUCTURE", "UPDATE orgs_rpt_structs SET prds_dyna_fields = P1, tsks_dyna_fields = P2, wrks_dyna_fields = P3, cstmrs_dyna_fields = P4, rpts_dyna_fields = P5, last_updated = P6 WHERE idorgs = P7");
define("SQL_UPDATE_ORGS_RPQFILTERS", "UPDATE orgs_rpt_qfilters SET qfilters = P1, last_updated = P2 WHERE idorgs = P3");

define("SQL_UPDATE_SUPERADMIN_PWD", "UPDATE superadmin SET password = P1, last_updated = P2");
define("SQL_UPDATE_SUPERADMIN_LOGINTIME", "UPDATE superadmin SET lastlogin = currentlogin, currentlogin = P1");
define("SQL_UPDATE_USER_LOGINTIME", "UPDATE orgs SET lastlogin = currentlogin, currentlogin = P1 WHERE name = P2");
define("SQL_UPDATE_USER_PWD", "UPDATE orgs SET password = P1, last_updated = P2 WHERE idorgs = P3");

define("SQL_UPDATE_USER_ORGS_PRDS", "UPDATE org_prds_P1 SET field_id_values = P2, last_updated = P3 WHERE idorg_prds = P4");
define("SQL_UPDATE_USER_ORGS_TSKS", "UPDATE org_tsks_P1 SET field_id_values = P2, last_updated = P3 WHERE idorg_tsks = P4");
define("SQL_UPDATE_USER_ORGS_WRKS", "UPDATE org_wrks_P1 SET field_id_values = P2, last_updated = P3 WHERE idorg_wrks = P4");
define("SQL_UPDATE_USER_ORGS_CSTMRS", "UPDATE org_cstmrs_P1 SET field_id_values = P2, last_updated = P3 WHERE idorg_cstmrs = P4");
define("SQL_UPDATE_USER_ORGS_RPTS", "UPDATE org_rpts_P1 SET sub_by = P2, clientname = P3, data = P4, update_his = CONCAT(update_his, P5), last_updated = P6 WHERE idorg_rpts = P7");



define("SQL_DELETE_XEDIT_DYNA_VALUES", "DELETE FROM xedit_dyna_values");
define("SQL_DELETE_XEDIT_DYNA_VALUES_FILTERED", "DELETE FROM xedit_dyna_values WHERE dyna_type = P1 AND dyna_id = P2");

define("SQL_DELETE_ORGS_PRDTS", "DELETE FROM orgs_prdts WHERE idorgs = P1");
define("SQL_DELETE_ORGS_TSKS", "DELETE FROM orgs_tsks WHERE idorgs = P1");
define("SQL_DELETE_ORGS_WRKS", "DELETE FROM orgs_wrks WHERE idorgs = P1");
define("SQL_DELETE_ORGS_CSTMRS", "DELETE FROM orgs_cstmrs WHERE idorgs = P1");
define("SQL_DELETE_ORGS", "DELETE FROM orgs WHERE idorgs = P1");

define("SQL_DELETE_ORGS_TEMPLATES", "DELETE FROM orgs_rpt_templates WHERE idorgs = P1");
define("SQL_DELETE_ORGS_RPT_STRUCTURE", "DELETE FROM orgs_rpt_structs WHERE idorgs = P1");
define("SQL_DELETE_ORGS_RPQFILTERS", "DELETE FROM orgs_rpt_qfilters WHERE idorgs = P1");

define("SQL_DELETE_USER_ORGS_PRDS", "DELETE FROM org_prds_P1 WHERE idorg_prds = P2");
define("SQL_DELETE_USER_ORGS_TSKS", "DELETE FROM org_tsks_P1 WHERE idorg_tsks = P2");
define("SQL_DELETE_USER_ORGS_WRKS", "DELETE FROM org_wrks_P1 WHERE idorg_wrks = P2");
define("SQL_DELETE_USER_ORGS_CSTMRS", "DELETE FROM org_cstmrs_P1 WHERE idorg_cstmrs = P2");
define("SQL_DELETE_USER_ORGS_RPTS", "DELETE FROM org_rpts_P1 WHERE idorg_rpts = P2");



define("SQL_CREATE_TABLE_ORG_PRDS", "CREATE TABLE `org_prds_P1` (
  `idorg_prds` int(11) NOT NULL AUTO_INCREMENT,
  `field_id_values` text COLLATE utf8_unicode_ci,
  `created_on` datetime DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`idorg_prds`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

define("SQL_CREATE_TABLE_ORG_TSKS", "CREATE TABLE `org_tsks_P1` (
  `idorg_tsks` int(11) NOT NULL AUTO_INCREMENT,
  `field_id_values` text COLLATE utf8_unicode_ci,
  `created_on` datetime DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`idorg_tsks`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

define("SQL_CREATE_TABLE_ORG_WRKS", "CREATE TABLE `org_wrks_P1` (
  `idorg_wrks` int(11) NOT NULL AUTO_INCREMENT,
  `field_id_values` text COLLATE utf8_unicode_ci,
  `created_on` datetime DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`idorg_wrks`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

define("SQL_CREATE_TABLE_ORG_CSTMRS", "CREATE TABLE `org_cstmrs_P1` (
  `idorg_cstmrs` int(11) NOT NULL AUTO_INCREMENT,
  `field_id_values` text COLLATE utf8_unicode_ci,
  `created_on` datetime DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`idorg_cstmrs`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

define("SQL_CREATE_TABLE_ORG_RPTS", "CREATE TABLE `org_rpts_P1` (
  `idorg_rpts` int(11) NOT NULL AUTO_INCREMENT,
  `sub_datetime` datetime DEFAULT NULL,
  `sub_by` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `clientname` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coordinates` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8_unicode_ci,
  `update_his` text COLLATE utf8_unicode_ci,
  `created_on` datetime DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`idorg_rpts`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");



define("SQL_DROP_TABLE_ORG_PRDS", "DROP TABLE org_prds_P1");
define("SQL_DROP_TABLE_ORG_TSKS", "DROP TABLE org_tsks_P1");
define("SQL_DROP_TABLE_ORG_WRKS", "DROP TABLE org_wrks_P1");
define("SQL_DROP_TABLE_ORG_CSTMRS", "DROP TABLE org_cstmrs_P1");
define("SQL_DROP_TABLE_ORG_RPTS", "DROP TABLE org_rpts_P1");


?>