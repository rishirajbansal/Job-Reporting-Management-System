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

require_once(dirname(__FILE__) . "/../common.php");


define('STRING_NONE', "none");
define('STRING_EMPTY', "");

define('SUBMISSION_MSG_TYPE_SUCCESS', 1);
define('SUBMISSION_MSG_TYPE_COMPLETESUCCESS', 2);
define('SUBMISSION_MSG_TYPE_MESSAGE', 3);
define('SUBMISSION_MSG_TYPE_ERROR', 4);
define('SUBMISSION_MSG_TYPE_CRITICALERROR', 5);


define('DYNAFIELDS_PRODUCT_CBID_PREFIX', 'p_');
define('DYNAFIELDS_TASK_CBID_PREFIX', 't_');
define('DYNAFIELDS_WORKER_CBID_PREFIX', 'w_');
define('DYNAFIELDS_CUSTOMER_CBID_PREFIX', 'c_');
define('DYNAFIELDS_REPORTING_CBID_PREFIX', 'r_');

define('DYNAFIELDS_XEDIT_PREFIX', 'x-');

define('DYNAFIELDS_LIST_VALUES_LOCALE_NA', '{0}');
define('DYNAFIELDS_LIST_VALUES_LOCALE_SEPERATOR', '<|>');

define('DYNAFIELDS_LIST_VALUES_SEPERATOR', '|');
define("DYNAFIELD_IDS_SEPERATOR", "|");
define("FIELDID_VALUE_SEPERATOR", ":");
define("FIELDID_VALUE_DATASET_SEPERATOR", "|");
define("MULTIPLE_VALUES_SEPERATOR", "|");
define("FIELD_2TB_VALUES_SEPERATOR", "<>");
define("FIELD_2TB_VALUES_DATASET_SEPERATOR", ",");
define("LIST_VALUES_SEPERATOR", ",");
define("MENU_QUERY_FILTERS_SEPERATOR", ",");
define("COORDNIATES_SEPERATOR", "|");
define("TIME_CONTROL_SEPERATOR", ".");

define("XEDIT_VALUES_SPEERATOR_ON_SUBMIT", ",");
define("XEDIT_VALUES_SPEERATOR_LOCALES", "<|>");

define("DYNAFILE_CONTROLTYPE", "Constants_MSG_10");

define("FIELD_VALUE_NOTFOUND", "NA");

define("DYNAFIELDS_TYPE_PRODUCT", "prds");
define("DYNAFIELDS_TYPE_TASKS", "tsks");
define("DYNAFIELDS_TYPE_WORKERS", "wrks");
define("DYNAFIELDS_TYPE_CUSTOMERS", "cstmrs");

define("ORG_LIST_MODE_VIEW", "view");
define("ORG_LIST_MODE_EDIT", "edit");
define("ORG_LIST_MODE_ACTIVATE", "activate");
define("ORG_LIST_MODE_DEACTIVATE", "deactivate");
define("ORG_LIST_MODE_DELETE", "delete");

define("ORG_UPDATE_PART_ORG_DETAILS", "orgDetails");
define("ORG_UPDATE_PART_ORG_COBRADING", "cobrand");
define("ORG_UPDATE_PART_ORG_PRODUCT_DETAILS", "prdsDetails");
define("ORG_UPDATE_PART_ORG_TASK_DETAILS", "tsksDetails");
define("ORG_UPDATE_PART_ORG_WORKER_DETAILS", "wrksDetails");
define("ORG_UPDATE_PART_ORG_CUSTOMER_DETAILS", "cstmrsDetails");

define("ORG_ACTIVATED", 1);
define("ORG_DEACTIVATED", 0);

define("ORG_TMPL_LIST_MODE_NEW", "new");
define("ORG_TMPL_LIST_MODE_EDIT", "edit");
define("ORG_TMPL_LIST_MODE_DELETE", "delete");

define("ORG_RPT_STRUCT_LIST_MODE_NEW", "new");
define("ORG_RPT_STRUCT_LIST_MODE_EDIT", "edit");
define("ORG_RPT_STRUCT_LIST_MODE_DELETE", "delete");

define("ORG_USERRPT_LIST_MODE_VIEW", "view");
define("ORG_USERRPT_LIST_MODE_EDIT", "edit");
define("ORG_USERRPT_LIST_MODE_EDIT_PUBLISH", "publish");
define("ORG_USERRPT_LIST_MODE_DELETE", "delete");
define("ORG_USERRPT_LIST_MODE_CALL_FROM_PUBLISH_ENGINE", "publishEngine");
define("ORG_USERRPT_LIST_REPORT_SHOW", "show");
define("ORG_USERRPT_LIST_REPORT_EXPORT", "export");

define("SETTINGS_MODE_NEWPASSWORD", "newPwd");


define("DYNA_CONTROL_TYPE_TEXT", "Text Box");
define("DYNA_CONTROL_TYPE_TEXTAREA", "Textarea");
define("DYNA_CONTROL_TYPE_COMBO", "Combo Box");
define("DYNA_CONTROL_TYPE_CHECKBOX", "Checkbox");
define("DYNA_CONTROL_TYPE_DATE", "Date");
define("DYNA_CONTROL_TYPE_TIME", "Time");
define("DYNA_CONTROL_TYPE_DYNAMIC_TEXTBOXES_2", "Dyna Text Box|2");
define("DYNA_CONTROL_TYPE_IMAGE", "Image");
define("DYNA_CONTROL_TYPE_SIGNPAD", "Signpad");

define("USERDYNAFIELDS_LIST_MODE_NEW", "new");
define("USERDYNAFIELDS_LIST_MODE_VIEW", "view");
define("USERDYNAFIELDS_LIST_MODE_EDIT", "edit");
define("USERDYNAFIELDS_LIST_MODE_DELETE", "delete");


define("ORG_LIST_DEFAULT", "default");
define("ORG_LIST_TEMPLATES", "listTemplates");
define("ORG_LIST_RPTSTRUCT", "listRptStructs");
define("ORG_LIST_QFILTERS", "listQFilters");

define("ORG_FOLDER_LOGO", "logo");
define("REPORT_NAMING_PREFIX", "JobReport-R");
define("REPORT_NAMING_FOLDERNAME_PREFIX", "R");
define("REPORT_DATA_IMAGE_TYPE_PHOTO", "1");
define("REPORT_DATA_IMAGE_TYPE_SIGN", "2");
define("REPORT_DATA_IMAGE_TYPE_PHOTO_PREFIX", "P_");
define("REPORT_DATA_IMAGE_TYPE_SIGN_PREFIX", "S_");
define("REPORT_DATA_IMAGE_URI_NAME", "image");


define("DATE_FORMAT", "dd/mm/yy");
define("DATEFORMAT_UPDATE_HIS", "M d, Y h:i A");
define("DATEFORMAT_LOGIN_TIME", "M d, Y h:i A");
define("DATEFORMAT_EXPORT_FILE", "Mj Y h\h_i\m_s\s");
define("DATEFORMAT_EXPORT_FILE_SEARCH_CRITERIA_DATE", "F j, Y");


define("DYNAFIELDS_FIELDID_HTMLNAME_CUSTOMER_NAME", "cstmr_name");
define("DYNAFIELDS_FIELDID_HTMLNAME_WORKER_NAME", "wrk_name");
define("DYNAFIELDS_FIELDID_HTMLNAME_TASK_STATUS", "tsk_status");

define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_PRODUCTSUSED_NAME", "rpt_prdqtylist");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_SERVICESTART_NAME", "rpt_sstime");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_SERVICEEND_NAME", "rpt_setime");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_INSURANCE", "rpt_insu");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_STARTTIME", "rpt_sstime");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_ENDTIME", "rpt_setime");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_EXTRAS", "rpt_extra");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_EUROS", "rpt_euros");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_DURATION", "rpt_dura");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_DESCRIPTION", "rpt_desc");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_DATAREPORT", "rpt_dtrpt");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_SIGNER", "rpt_signer");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_OBSERVATIONS", "rpt_obs");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_INCIDENCES", "rpt_incd");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_TASKSLIST", "rpt_tsklist");
define("DYNAFIELDS_FIELDID_HTMLNAME_RPT_MEASUREMENTSLIST", "rpt_measurelist");

define("DYNAFIELDS_TABLE_WORKER", "wrks_fields");
define("DYNAFIELDS_TABLE_CUSTOMER", "cstmrs_fields");
define("DYNAFIELDS_TABLE_TASK", "tsks_fields");
define("DYNAFIELDS_TABLE_ID_PRODUCT", "idprdts_fields");
define("DYNAFIELDS_TABLE_ID_TASK", "idtsks_fields");
define("DYNAFIELDS_TABLE_ID_WORKER", "idwrks_fields");
define("DYNAFIELDS_TABLE_ID_CUSTOMER", "idcstmrs_fields");
define("DYNAFIELDS_TABLE_ID_REPORT", "idrpts_fields");

define("LOCATION_GPS_NOT_ENABLED", "0");
define("LOCATION_GPS_ENABLED", "1");
define("LOCATION_GEOCODE_TIMEDOUT", "2");
define("LOCATION_NO_LOCATION_FOUND", "3");

define("LOCATION_MSG_NO_LOCATION_FOUND", "Constants_MSG_1");
define("LOCATION_MSG_GPS_NOT_ENABLED", "Constants_MSG_2");
define("LOCATION_MSG_GPS_ENABLED", "Constants_MSG_3");
define("LOCATION_MSG_GEOCODE_TIMEDOUT", "Constants_MSG_4");

define("UI_STRING_REPORT_TCW_LOCATION_NOT_FOUND", "Constants_MSG_5");


define("EXPORT_FILENAME_REPORT_TCW", "Constants_MSG_6");
define("EXPORT_FILENAME_REPORT_CALWHRS", "Constants_MSG_7");
define("EXPORT_FILENAME_REPORT_TTLPRDQTY", "Constants_MSG_8");


define("RPT_TEMPLATE_MODEL_1", "CODEPSA");
define("RPT_TEMPLATE_MODEL_2", "EUROPA");

define("RPT_PUBLISHING_DOCUMENT_PLACEHOLDER_NA", "Constants_MSG_9");


define("RPT_MAIL_LAYOUT", "report_email_template_");
define("MAIL_TEMPLATE_EXT", ".html");



?>