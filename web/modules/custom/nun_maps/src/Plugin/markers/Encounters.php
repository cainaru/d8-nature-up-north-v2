<?php
namespace Drupal\nun_maps\Plugin\markers;

use Drupal\map_component\MarkerSourceBase;

/**
 * @MarkerSource(
 *   id = "encounter",
 * )
 */
class Encounters extends MarkerSourceBase {
/*
SELECT
    n.nid id,
    data.title title,
    descript.field_encounter_description_value as description,
    loc.field_encounter_location_lat lat,
    loc.field_encounter_location_lng lon
FROM
    node n
INNER JOIN
    node_revision r
ON
    n.vid=r.vid AND n.nid=r.nid
JOIN
    node_field_data data
ON
    data.vid=r.vid AND data.nid=r.nid
JOIN
    node__field_encounter_description descript
ON
    descript.entity_id=r.nid AND descript.revision_id=r.vid
RIGHT JOIN
    node__field_encounter_location loc
ON
    loc.entity_id=r.nid AND loc.revision_id=r.vid
WHERE
    n.type='encounter'
ORDER BY
    r.revision_timestamp DESC
*/
    protected function query() {
        $query = $this->database()->select('node','n');
        $query->addField('n','nid','id');
        $query->addField('n','type','type');
        $query->innerJoin('node_revision','r','n.vid=r.vid AND n.nid=r.nid');

        $query->join('node_field_data','data','data.vid=r.vid AND data.nid=r.nid AND data.status=1');
        $query->addField('data','title');

        $query->leftJoin('node__field_encounter_description','descript','descript.entity_id=r.nid AND descript.revision_id=r.vid');
        $query->addField('descript','field_encounter_description_value','description');

        $query->rightJoin('node__field_encounter_location','loc','loc.entity_id=r.nid AND loc.revision_id=r.vid');
        $query->addField('loc','field_encounter_location_lat','lat');
        $query->addField('loc','field_encounter_location_lng','lon');

        $query->where('n.type=\'encounter\'');
        $query->orderBy('r.revision_timestamp','DESC');

        return $query;
    }

    public function getIcon() { return '/modules/custom/nun_maps/img/marker-icon.png'; }


}
