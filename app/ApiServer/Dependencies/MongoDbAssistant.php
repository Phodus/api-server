<?php

namespace ApiServer;

class MongoDbAssistant {

    public static function findOnlySubDocument($collectionName, $subKey, $id) {
        $db = ConfigManager::initMongoDbConn();
        $collection = $db->$collectionName;
        $result = $collection->aggregate(array(
            '$project' => array(
                $subKey => '$'.$subKey
            )
        ),
            array(
                '$unwind' => '$'.$subKey
            ),
            array(
                '$group' => array(
                    "_id" => '$'.$subKey.'._id',
                    "element" => array(
                        '$first' => '$'.$subKey
                    )
                )
            ),
            array(
                '$match' => array(
                    "_id" => new \MongoId($id)
                )
            ));

        if(isset($result['result'][0]['element'])) {
            return $result['result'][0]['element'];
        }
        return false;
    }
}
