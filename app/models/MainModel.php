<?php

namespace app\models;

use RedBeanPHP\R;

class MainModel extends AppModel
{
    /**
     * получить брендны
     * @return array брендны
     */
    public function getBrands(): array
    {
        return R::find('brand', 'LIMIT 3');
    }

    /**
     * получить хиты
     * @return array хиты
     */
    public function getHits(): array
    {
        return R::find('product', "hit = '1' AND status = '1' LIMIT 8");
    }
}
