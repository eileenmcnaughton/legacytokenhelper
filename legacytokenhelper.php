<?php

require_once 'legacytokenhelper.civix.php';
// phpcs:disable
use Civi\Token\Event\TokenRenderEvent;
use CRM_Legacytokenhelper_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function legacytokenhelper_civicrm_config(&$config) {
  _legacytokenhelper_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function legacytokenhelper_civicrm_xmlMenu(&$files) {
  _legacytokenhelper_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function legacytokenhelper_civicrm_install() {
  _legacytokenhelper_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function legacytokenhelper_civicrm_postInstall() {
  _legacytokenhelper_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function legacytokenhelper_civicrm_uninstall() {
  _legacytokenhelper_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function legacytokenhelper_civicrm_enable() {
  _legacytokenhelper_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function legacytokenhelper_civicrm_disable() {
  _legacytokenhelper_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function legacytokenhelper_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _legacytokenhelper_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function legacytokenhelper_civicrm_managed(&$entities) {
  _legacytokenhelper_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function legacytokenhelper_civicrm_caseTypes(&$caseTypes) {
  _legacytokenhelper_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function legacytokenhelper_civicrm_angularModules(&$angularModules) {
  _legacytokenhelper_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function legacytokenhelper_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _legacytokenhelper_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function legacytokenhelper_civicrm_entityTypes(&$entityTypes) {
  _legacytokenhelper_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_themes().
 */
function legacytokenhelper_civicrm_themes(&$themes) {
  _legacytokenhelper_civix_civicrm_themes($themes);
}

/**
 * Implements hook_civicrm_container().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_container/
 */
function legacytokenhelper_civicrm_container($container) {
  $container->addResource(new \Symfony\Component\Config\Resource\FileResource(__FILE__));
  $container->findDefinition('dispatcher')->addMethodCall('addListener',
    ['civi.token.eval', 'legacytokenhelper_evaluate_tokens', 700]
  );
  $container->findDefinition('dispatcher')->addMethodCall('addListener',
    ['civi.token.render', 'legacytokenhelper_render_contact_to_tpl', 700],
  );
}

/**
 * Symfony event for civi.token.eval
 *
 * @param \Civi\Token\Event\TokenValueEvent $event
 */
function legacytokenhelper_evaluate_tokens(Civi\Token\Event\TokenValueEvent $event) {
  $messageToken = $event->getTokenProcessor()->getMessageTokens();
  $returnProperties = [];
  if (isset($messageToken['contact'])) {
    foreach ($messageToken['contact'] as $key => $value) {
      $returnProperties[$value] = 1;
    }
  }
  foreach ($event->getRows() as $row) {
    $contactId = $row->context['contactId'] ?? NULL;
    $caseId = $row->context['caseId'] ?? NULL;
    if ($contactId) {
      if (empty($row->context['contact'])) {
        $params = ['contact_id' => $contactId];
        if ($caseId) {
          $params['case_id'] = $caseId;
        }

        [$row->context['contact']] = CRM_Utils_Token::getTokenDetails($params,
          $returnProperties,
          FALSE,
          FALSE,
          NULL,
          $messageToken,
        );
      }
    }
  }
}

function legacytokenhelper_render_contact_to_tpl(TokenRenderEvent $e) {
  if (!empty($e->context['contact'])) {
     if (defined('CIVICRM_MAIL_SMARTY') && CIVICRM_MAIL_SMARTY) {
      $smarty = CRM_Core_Smarty::singleton();
      // also add the contact tokens to the template
      $smarty->assign_by_ref('contact', $e->context['contact']);
    }
  }
}

