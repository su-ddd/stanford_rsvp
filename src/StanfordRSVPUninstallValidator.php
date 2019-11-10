<?php
namespace Drupal\stanford_rsvp;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Extension\ModuleUninstallValidatorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Prevents module from being uninstalled whilst any Stanford RSVP nodes exist
 */

class StanfordRSVPUninstallValidator implements ModuleUninstallValidatorInterface {

  use StringTranslationTrait;

  /**
   * The entity query for node.
   *
   * @var \Drupal\Core\Entity\Query\QueryInterface
   */
  protected $entityQuery;

  /**
   * Constructs a new StanfordRSVPUninstallValidator.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   *   The entity query factory.
   */
  public function __construct(QueryFactory $query_factory) {
    $this->entityQuery = $query_factory
      ->get('node');
  }

  /**
   * {@inheritdoc}
   */
  public function validate($module) {
    $reasons = [];
    if ($module == 'stanford_rsvp') {
      // The Stanford RSVP node type is provided by the Stanford RSVP module. Prevent uninstall
      // if there are any nodes of that type.
      if ($this->hasStanfordRSVPNodes()) {
        $reasons[] = $this
          ->t('To uninstall Stanford RSVP, delete all content that has the Stanford RSVP content type');
      }
    }
    return $reasons;
  }

  /**
   * Determines if there is any Stanford RSVP nodes or not.
   *
   * @return bool
   *   TRUE if there are Stanford RSVP nodes, FALSE otherwise.
   */
  protected function hasStanfordRSVPNodes() {
    $nodes = $this->entityQuery
      ->condition('type', 'stanford_rsvp')
      ->accessCheck(FALSE)
      ->range(0, 1)
      ->execute();
    return !empty($nodes);
  }
}
