<?php

namespace Drupal\custom_schema_metatag\Plugin\metatag\Group;

use Drupal\metatag\Plugin\metatag\Group\GroupBase;

/**
 * @MetatagGroup(
 *   id = "schema_json_override",
 *   label = @Translation("Schema JSON Override"),
 *   description = @Translation("Custom JSON-LD structured data. When populated, replaces all output from the schema_metatag module."),
 *   weight = 100
 * )
 */
class SchemaJsonOverride extends GroupBase {
}
