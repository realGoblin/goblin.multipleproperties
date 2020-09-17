<?
Class goblin_multipleproperties extends CModule
{
  public function InstallEvents(){
        \Bitrix\Main\EventManager::getInstance()->registerEventHandler(
            "iblock",
            "OnIBlockPropertyBuildList",
            $this->MODULE_ID,
            "MultuProp\CIBlockOption",
            "GetUserTypeDescription"
        );
    }
    public function goblin_multipleproperties(){
        include("version.php");
        $this->MODULE_ID        = 'goblin.multipleproperties';
        $this->MODULE_VERSION      = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME        = $arModuleVersion["MODULE_NAME"];
        $this->MODULE_DESCRIPTION  = $arModuleVersion["MODULE_DESCRIPTION"];
        $this->PARTNER_NAME      = $arModuleVersion["PARTNER_NAME"];
        $this->PARTNER_URI       = $arModuleVersion["PARTNER_URI"];
        }
  function DoInstall()
  {
    global $DB, $APPLICATION, $step;
    RegisterModule($this->MODULE_ID);
    $this->InstallEvents();
    	// CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/goblin.multipleproperties/install/js/security", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/goblin.multipleproperties", true, true);
    // copy($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/goblin.multipleproperties/install/admin/multipleproperties.php",$_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/multipleproperties.php");
    copy($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/goblin.multipleproperties/admin/multipleproperties.js",$_SERVER["DOCUMENT_ROOT"]."/bitrix/js/multipleproperties.js");

    $APPLICATION->IncludeAdminFile(GetMessage("FORM_INSTALL_TITLE"),$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/goblin.multipleproperties/install/step.php");
    return true;
    //
  }
  function DoUninstall()
  {
    global $DB, $APPLICATION, $step;
    UnRegisterModuleDependences("iblock","OnIBlockPropertyBuildList","goblin_multipleproperties","CIBlockOption","GetUserTypeDescription");
    UnRegisterModule($this->MODULE_ID);
    // DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/goblin.multipleproperties/install/js/goblin.multipleproperties/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/goblin.multipleproperties");
    // unlink($_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/multipleproperties.php");
    unlink($_SERVER["DOCUMENT_ROOT"]."/bitrix/js/multipleproperties.js");
    $APPLICATION->IncludeAdminFile(GetMessage("FORM_INSTALL_TITLE"),$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/goblin.multipleproperties/install/unstep.php");
    return true;
    //
  }
}
?>
