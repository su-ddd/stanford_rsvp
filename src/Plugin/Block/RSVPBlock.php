<?php

namespace Drupal\stanford_rsvp\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an RSVP Block
 *
 * @Block(
 *   id = "stanford_rsvp_block",
 *   admin_label = @Translation("Stanford RSVP block"),
 *   deriver = "Drupal\stanford_rsvp\Plugin\Derivative\RSVPBlock"
 * )
 */

class RSVPBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var EntityViewBuilderInterface.
   */
  private $viewBuilder;

  /**
   * @var NodeInterface.
   */
  private $node;

  /**
   * Creates a RSVPBlock instance.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param array $plugin_definition
   * @param EntityManagerInterface $entity_manager
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->viewBuilder = $entity_manager->getViewBuilder('node');
    $this->nodeStorage = $entity_manager->getStorage('node');
    $this->node = $entity_manager->getStorage('node')->load($this->getDerivativeId());
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    if (!$this->node instanceof NodeInterface) {
      return;
    }
    $location = $this->node->get('field_stanford_rsvp_location')->getString();
    $zoom_id  = $this->node->get('field_stanford_rsvp_zoom_id')->getString();
    $info_url = $this->node->get('field_stanford_rsvp_info_url')->getString();
    $max      = $this->node->get('field_stanford_rsvp_max')->getString();
    $text     = $this->node->get('field_stanford_rsvp_text')->getValue();
    $date     = $this->node->get('field_stanford_rsvp_date')->getValue();
    $tickets  = $this->node->get('field_stanford_rsvp_ticket_types')->getValue();
    $user     = \Drupal::currentUser();

    $block_content = '';
    if ($user->isAuthenticated()) {
      // test if the user has an RSVP
      // build form for RSVP
      $block_content .= print_r($tickets, true);
    } else {
        $block_content .= '<p>' . t('Please log in to register.') . '</p>'; 
        $login_url = \Drupal\Core\Url::fromRoute('simplesamlphp_auth.saml_login');
        $login_url->setOptions(array('attributes' => array('class' => array('btn success'))));
        $login_url->setOptions(array('query' => \Drupal::service('redirect.destination')->getAsArray()));
        $block_content .= \Drupal\Core\Link::fromTextAndUrl('Log in', $login_url)->toString();
    }

//    $block_content = $location . ' ' . $zoom_id . ' ' . $info_url . ' ' . $rsvp_max . ' ' . print_r($text, true) . ' ' . print_r($date, true) . ' ' . print_r($tickets, true) . ' ' . print_r($user, true));
    $build = array('#markup' => $block_content);

//$this->node->get('field_stanford_rsvp_location')->getString()); //'hello'); //$this->viewBuilder->view($this->node, 'full');
    return $build;
  }
  
  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account, $return_as_object = FALSE) {
    return $this->node->access('view', NULL, TRUE);
  }

  public function getCacheMaxAge() {
    return 0;
  }
}
