
```
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
                    ])
                ], 
                ...
            ]
        ];
    }
```
