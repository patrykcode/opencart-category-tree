<?php
 chdir(__DIR__);
include_once '../config.php';
include_once './helper/connection.php';

class Categories
{
    //baza sklepu
    private $baza_oc = [
        'DB_USERNAME' => '',
        'DB_PASSWORD' => '',
        'DB_HOSTNAME' => '',
        'DB_DATABASE' => '',
    ];
    private $db = null;
    private $dblocal = null;
    private $_category_id = 0;
    private $limit = 10;

    public function __construct()
    {
        //baza lokalna
        $this->dblocal = db($this->baza_oc);
    }

    public function init()
    {
        $cats = $this->getCategory(0);
        // var_dump($cats);exit;

        $parent_id = 0;
        // var_dump($cats);
        foreach ($cats as $cat) {
            // var_dump($cat);
            $this->add($parent_id, $cat);
            
        }
    }

    private function add($parent_id=0, $data=[]){
        echo "\n {$data['name']}";
        $category_id = $this->checkCategoryExists($data['name'],$parent_id);
        
        if ($category_id === false) {
            $data['parent_id'] = $parent_id;
            $category_id = $this->addCategory($data);
        }else{

        }

        if($data['child']){
            foreach ($data['child'] as $row) {
                $this->add($category_id, $row);
            }
        }
        return false;
    }

/**
 * https://www.motosati.pl/ js do zbierania kategorii
 *        var s ="";
 *        [...document.querySelectorAll('.subcategories .wrap')].map((e)=>{
 *        var t = (e.innerText).replace(/\s\(\d*\)/,'')
 *        s+=`$temp('`+t+`',[]),
 *        `
 *        });
 *        s;
 * @return void
 */
    private function getCategory()
    {
        $temp = function($name,$child=[]){
            return [
                'parent_id' => 0,
                'name'=> $name,
                'child'=>$child
            ];
        };
        return [
            $temp('Części zamienne',[
                $temp('Używane',[
                    $temp('Motocykle', [
                        $temp('Yamaha'),
                        $temp('KTM'),
                        $temp('Honda'),
                        $temp('Suzuki'),
                        $temp('Kawasaki'),
                    ]),
                    $temp('ATV-Quad', [
                        $temp('Yamaha',[
                            $temp('Grizzly 600'),
                            $temp('Grizzly 700'),
                            $temp('Raptor 700'),
                            $temp('Raptor 660'),
                            $temp('Raptor 350'),
                            $temp('Raptor 250'),
                            $temp('YFZ 450'),
                            $temp('YZF 450R'),
                        ]),
                        $temp('Suzuki',[$temp('LTZ 400'),$temp('LTZ 450')]),
                        $temp('Polaris',[$temp('LTZ 400'),$temp('LTZ 450')]),
                        $temp('Can Am',[]),
                        $temp('Kawasaki',[$temp('Sportsman 500'),$temp('Sportsman 800'),$temp('RZR 800')]),
                        $temp(' MOTO'),
                    ]),
                    $temp('Skutery', [
                        $temp('Piaggio'),
                        $temp('Vespa'),
                        $temp('Kymco'),
                        $temp('Yamaha'),
                    ]),
                ]),
                $temp('nowe - oem parts',[
                $temp('NADWOZIE',[]),
                $temp('Układ napędowy',[]),
                $temp('Układ hamulcowy',[]),
                $temp('Układ Elektryczny',[]),
                $temp('Silnik',[]),
                $temp('Filtry',[]),
                $temp('Zawieszenie i Koła',[]),
                $temp('Tłumiki',[])
                ]),
            ]),
            $temp('Odzież',[
                $temp('Odzież Off-Road MX',[
                    $temp('Bluza Offroad Cross Enduro',[]),
                    $temp('Spodnie Cross Atv',[]),
                    $temp('Spodnie Enduro',[]),
                    $temp('Rękawice offroad cross',[]),
                    $temp('Kurtka Enduro Offroad',[])
                ]),
                $temp('Odzież Szosowo Turystyczna',[
                    $temp('Kurtki Skórzane',[]),
                    $temp('Kurtki Textylne',[]),
                    $temp('Kurtki Turystyczne',[]),
                    $temp('Spodnie Skórzane',[]),
                    $temp('Spodnie Textylne',[]),
                    $temp('Moto Jeansy',[]),
                    $temp('Kombinezony',[]),
                    $temp('Rękawice Krótkie',[]),
                    $temp('Rękawice Długie',[]),
                    $temp('Ochraniacze Szosa',[]),
                    $temp('Odzież przeciwdeszczowa',[]),
                    $temp('Plecaki',[]),
                    $temp('Kominiarka motocyklowa',[])
                ]),
                $temp('Ubiór i Ochrona Diecięca',[
                    $temp('Kurtki dziecięce',[]),
                    $temp('Gogle Dziecięce',[]),
                    $temp('Kaski dla dzieci',[]),
                    $temp('Buty dla dzieci',[]),
                    $temp('Bluzy motocyklowe dla dzieci',[]),
                    $temp('Spodnie dla dzieci',[]),
                    $temp('Rękawice dla dzieci',[]),
                    $temp('Ochraniacze dla dzieci',[])
                ]),
                $temp('Odzież motocyklowa Damska',[
                    $temp('Kurtki Motocyklowe Damskie',[]),
                    $temp('Rękawice motocyklowe Damskie',[]),
                    $temp('Kaski Damskie',[]),
                    $temp('Spodnie Motocyklowe Damskie',[]),
                    $temp('Off Road',[]),
                    $temp('Buty Damskie motocyklowe',[])
                ]),
                $temp('Snow Ubiór',[
                    $temp('Kominiarki',[]),
                    $temp('Buty',[]),
                    $temp('Kombinezony',[]),
                    $temp('Kaski',[]),
                    $temp('Rękawice',[]),
                    $temp('Kurtki',[]),
                    $temp('Spodnie',[]),
                    $temp('Gogle zimowe',[])
                ]),
                $temp('Buty motocyklowe',[
                    $temp('Buty szosowe sport',[]),
                    $temp('Buty szosowe krótkie',[]),
                    $temp('Buty szosowe turystyczne',[]),
                    $temp('Buty offroad Cross i Atv',[]),
                    $temp('Buty Chopper',[])
                ]),
                $temp('Ochraniacze i Bielizna',[
                    $temp('Ortezy',[]),
                    $temp('Zbroja Buzer',[]),
                    $temp('Ochraniacze kolan',[]),
                    $temp('Nałokietniki ochroniacz łokcia',[]),
                    $temp('Szorty ochronne',[]),
                    $temp('Ochraniacz szyi Stabilizator karku',[]),
                    $temp('Pasy Nerkowe',[]),
                    $temp('Odzież termoaktywna',[]),
                ]),
                $temp('Odzież codzienna',[
                    $temp('Czapka',[]),
                    $temp('T-shirt',[])
                ])
            ]),
            $temp('Kkaski i gogle',[
                $temp('Integralny',[]),
                $temp('Crosowy i Offroad',[]),
                $temp('Kask Otwarty',[]),
                $temp('Szczękowy',[]),
                $temp('Dual Enduro',[]),
                $temp('Gogle',[]),
                $temp('Akcesoria do gogli i kasków',[])
            ]),
            $temp('Oleje i chemia',[
                $temp('Oleje silnikowe 4T',[]),
                $temp('Oleje silnikowe 2T',[]),
                $temp('Oleje do quadów',[]),
                $temp('Oleje do skuterów',[]),
                $temp('Oleje do amortyzatorów',[]),
                $temp('Oleje przekładniowe',[]),
                $temp('Smary do łańcuchów',[]),
                $temp('Płyn Chłodniczy',[]),
                $temp('Środki do filtrów powietrza',[]),
                $temp('Płyn Hamulcowy',[]),
                $temp('Środki czyszczące',[]),
                $temp('Chemia warsztatowa',[]),
                $temp('Smary',[]),
                $temp('Dodatki do paliw',[])
            ]),
            $temp('Akcesoria',[
                $temp('Uchwyty telefoniczne',[]),
                $temp('Narzędzia warsztatowe',[]),
                $temp('Gadżety motocyklowe',[]),
                $temp('Lusterka',[]),
                $temp('Stojaki motocyklowe',[]),
                $temp('Tankpady i okleiny',[]),
                $temp('Kufry Bagaże Pokrowce',[]),
                $temp('Zabezpieczenia Moto',[])
            ]),
            $temp('Outlet',[

            ]),
        ];
    }

    private function addCategory($data = [], $store_id = 0, $lang_id = 2)
    {
        try {
            $this->dblocal->beginTransaction();
            $this->dblocal->query('INSERT INTO `oc_category` SET 
            `parent_id`=?,
            `top`=1,
            `column`=1,
            `status`=1,
            `date_added`=NOW(),
            `date_modified`=NOW(),
            `_import`=1,
            `_old_id`=0', [isset($data['parent_id']) ? $data['parent_id'] : 0]);
            if ($category_id = $this->dblocal->getLastid()) {
                $this->dblocal->query("INSERT INTO `oc_category_description` SET 
                category_id=?,
                language_id=?,
                name=?,
                description='',
                meta_title=?,
                meta_description='',
                meta_keyword=''", [
                    $category_id,
                    $lang_id,
                    $data['name'],
                    $data['name'],
                ]);

                $this->dblocal->query('INSERT INTO `oc_category_to_store` SET 
                category_id=?,store_id=?', [$category_id, $store_id]);
                $this->dblocal->commit();

                return $category_id;
            }
        } catch (\PDOException $e) {
            $this->dblocal->rollBack();
        }

        return false;
    }

    private function checkCategoryExists($name = '1', $parent_id = 0)
    {
        $query = $this->dblocal->select(
            'SELECT * FROM `oc_category_description` cd 
        INNER JOIN `oc_category` c ON c.category_id=cd.category_id 
        WHERE cd.name LIKE ? AND c.parent_id=?',
            [$name, $parent_id]
        );

        return $query->count() ? $query->first('category_id')['category_id'] : false;
    }
}

$obj = new Categories();
$obj->init();
