<?php

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    public $actsAs = array('Containable');
    public $recursive = -1;

    public function counterIncrement($id) {
        if (Configure::read('debug') === 0 && $_SERVER['HTTP_HOST'] !== 'localhost') {
            $cacheKey = "{$this->name}/Counter/{$id}";
            $cachedCounter = Cache::read($cacheKey);
            if (false === Cache::increment($cacheKey) || false === $cachedCounter) {
                Cache::write($cacheKey, 1);
            }

            if ((int) $cachedCounter >= 10) {
                Cache::write($cacheKey, 1);
                $this->updateAll(array(
                    'count_daily' => "count_daily + {$cachedCounter}",
                    'count_all' => "count_all + {$cachedCounter}",
                        ), array(
                    "{$this->name}.id" => $id,
                ));
            }
        }
    }

}
