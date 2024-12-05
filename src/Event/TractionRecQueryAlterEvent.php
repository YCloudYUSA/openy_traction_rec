<?php

namespace Drupal\openy_traction_rec\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event is invoked after Traction Rec fetching and dumping JSON files.
 */
class TractionRecQueryAlterEvent extends Event {

  /**
   * The event name.
   */
  const EVENT_NAME = 'openy_traction_rec.user_membership_query_alter';

  /**
   * The directory with fetched files.
   *
   * @var string
   */
  protected $sqlQuery;

  /**
   * Constructors the event class.
   *
   * @param string $sqlQuery
   *   The updated SQL query.
   */
  public function __construct(string $sqlQuery) {
    $this->sqlQuery = $sqlQuery;
  }

  /**
   * Provides the updated query for requesting User.
   *
   * @return string
   *   The new query for requesting User.
   */
  public function getSqlQuery(): string {
    return $this->sqlQuery;
  }

  /**
   * Set query for requesting User.
   *
   * @return string
   *   The new query for requesting User.
   */
  public function setSqlQuery(string $newQuery): void {
    $this->sqlQuery = $newQuery;
  }

}
