 <?php
/**
 * Description of sugarRepair
 *
 * @author Mauricio Herrera <mwherrera@plus-projects.com>
 *         @date 10/03/2014
 */

require_once '../../autoload.php';

# Put us in the right directory

function change_to_sugar_directory($dir_path) {    
    if (file_exists($dir_path)) {
        set_include_path(get_include_path() . PATH_SEPARATOR . $dir_path);
        chdir($dir_path);
        if (file_exists('include/entryPoint.php')) {
            echo("Repairing: " . $dir_path . "\n");
        } else {
            die("Cannot find " . $dir_path . "/include/entryPoint.php\n");
        }
    } else {
        die("Directory not found: " . $dir_path . "\n");
    }
}

function setup_environment() {
    global $sugar_config;
    # Setup the language
    if (empty($current_language)) {
        $current_language = $sugar_config['default_language'];
    }
    $app_list_strings = return_app_list_strings_language($current_language);
    $app_strings = return_application_language($current_language);
    # Setup the current user
    global $current_user;
    $current_user = new User();
    $current_user->getSystemUser();
    //echo '<pre>';
    //var_dump($current_user);
}

function repair() {
    $repair = new RepairAndClear();
    $repair->repairAndClearAll(array('clearAll'), array(translate('LBL_ALL_MODULES')), true, false, '');
    //remove the js language files
    LanguageManager::removeJSLanguageFiles();
    //remove language cache files
    LanguageManager::clearLanguageCache();
    return true;
}

function teardown_environment() {
    $exit_on_cleanup = true;
    sugar_cleanup(false);
    // some jobs have annoying habit of calling sugar_cleanup(), and it can be called only once
    // but job results can be written to DB after job is finished, so we have to disconnect here again
    // just in case we couldn't call cleanup
    if (class_exists('DBManagerFactory')) {
        $db = DBManagerFactory::getInstance();
        $db->disconnect();
    }
    if ($exit_on_cleanup)
        exit;
}
echo 'reparando...';
change_to_sugar_directory(CRM_PATH);
require_once(CRM_PATH.'/include/entryPoint.php');
require_once(CRM_PATH.'/modules/Administration/QuickRepairAndRebuild.php');
setup_environment();
repair();
teardown_environment();
