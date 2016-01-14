<?php

App::uses('AppController', 'Controller');

class CrontabMultiLuckyNumbersController extends AppController
{
	const LIMIT_DATE = 15;

	public $uses = array(
		'NumberLuck',
		'DateLuck',
	);

	private $mDay;
	private $mMonth;
	private $mYear;
	private $mDateToSave;
	private $mRESULT;

	public function index()
	{
		$this->mDay = $this->getDayReport();
        $this->mMonth = $this->getMonthReport();
        $this->mYear = $this->getYearReport();

        $this->autoRender = false;
        set_time_limit(-1);
        $this->getResultToCalculate();

        $this->getLuckyNumber();
	}

	/**
	* Hàm xử lý, tính toán cầu
	*/
	public function getLuckyNumber()
	{
		$arr_result = array();

		$allResult1 = $this->parseResult($this->mRESULT[0]); //phân tách kết quả ngày 1
		
		$arrCouple1 = $this->getMultiLoto($this->mRESULT[0]); //mảng các lô về lộn của ngày 1

		//debug($arrCouple1);die; //phân tách kết quả ngày 1
		if (!empty($arrCouple1)) {
			$allResult2 = $this->parseResult($this->mRESULT[1]);
			
			foreach ($arrCouple1 as $num) {

				$split = array_map('intval', str_split($num)); //mảng gồm 2 chữ số. ví dụ cặp 51-15 trả về mảng gồm 1,5

				$isDouble = false;
				if($num%11 == 0){
					$isDouble = true;
				}

				$valid2 = array();//mảng các chữ số thỏa mãn, kèm theo key (vi tri)

				for ($i=0; $i < count($allResult2) ; $i++) { 
					if( in_array($allResult2[$i], $split) ){ //nếu ko phải 1 trong 2 chữ số của cặp
						$valid2[$i] = $allResult2[$i];
					}
				}

				$arrayPosition2 = array();
				if(!empty($valid2)){
					$clone2 = $valid2;
					
					while (!empty($clone2)) {
						foreach ($valid2 as $key => $value) {
							unset($clone2[$key]);
							foreach ($clone2 as $k => $v) {

								if(!$isDouble){
									if($value != $v){

										$arrayPosition2[] = [$key,$k];
									}
								}
								else{
									$arrayPosition2[] = [$key,$k];
								}

							}
						}
					}
					
				}

				$arrayPosition3 = array();

				if(!empty($arrayPosition2)){
					$arrCouple2 = $this->getMultiLoto($this->mRESULT[1]);
					
					if (empty($arrCouple2)) {
						die('ngày 2 ko có lô về lộn.');
					}else{
						$allResult3 = $this->parseResult($this->mRESULT[2]);
					
						foreach ($arrayPosition2 as $position) {
							$xx = $allResult3[$position[0]].$allResult3[$position[1]];
							$yy = $allResult1[$position[0]].$allResult1[$position[1]];
							
							if( $this->checkNumberCouple($arrCouple2,$xx) ){
								$temp = array(
									'position_1'	=>	$position[0],
									'position_2'	=>	$position[1],
									'number'		=>	$yy
								);
								$arrayPosition3[] = $position;
								
								$arr_result[2][] = $temp;
							}
						}
					}
					
				}
				
			}
			

			if( !empty($arrayPosition3) ){//nếu có cầu 2 ngày thì mới tính tiếp các cầu dài hơn
				
				$indexLoop = 4;
				$haveResult = true;
				${"arrCouple".($indexLoop-1)} = $this->getMultiLoto($this->mRESULT[$indexLoop-2]);

				
				while ( $haveResult ) {
					if(empty( ${"arrCouple".($indexLoop-1)} ) || !isset($this->mRESULT[$indexLoop-1]) || !isset($arr_result[$indexLoop-2]) ){

						$haveResult = false;

					}else{

						${"allResult".$indexLoop} = $this->parseResult($this->mRESULT[$indexLoop-1]);

						foreach (${"arrayPosition".($indexLoop-1)} as $position) {

							$xx = ${"allResult".$indexLoop}[$position[0]].${"allResult".$indexLoop}[$position[1]];
							$yy = $allResult1[$position[0]].$allResult1[$position[1]];
							
							if( $this->checkNumberCouple(${"arrCouple".($indexLoop-2)},$xx) ){
								
								${"arrayPosition".($indexLoop)}[] = $position;
								$temp = array(
									'position_1'	=>	$position[0],
									'position_2'	=>	$position[1],
									'number'		=>	$yy
								);
								
								$arr_result[$indexLoop-1][] = $temp;
							}
						}
						
						$indexLoop++;
					}
				}

			}
			//debug($arr_result);die;
			if( !empty($arr_result) ){
				$this->saveData( $arr_result );
			}
			debug('Success.');

		}else{
			debug('Ngày gần nhất khồn có loto nào về nhiều nháy.');
		}
	}

	protected function saveData( $arr_result )
	{
		$this->NumberLuck->deleteAll( array(
				'date'			=>  $this->mDateToSave,
				'region_code'   =>  'thu-do',
				'type'          =>  'CAU',
			)
		);
		$numbers = array();
		foreach ($arr_result as $key => $item) {
			foreach ($item as $number) {
				$saveData = array(
					'date'          =>  (int)$this->mDateToSave,
			        'region_code'   =>  'thu-do',
			        'type'          =>  'CAU',
			        'span'          =>  $key,
			        'number'        =>  $number['number'], 
			        'first_loc'     =>  $number['position_1'],
			        'last_loc'      =>  $number['position_2'],
				);
				$this->NumberLuck->create();
				$this->NumberLuck->save($saveData);
				//echo $this->get_last_query($this->NumberLuck);

			}

		}

		$dateLuckData = array(
			'date'          =>  (int)$this->mDateToSave,
	        'region_code'   =>  'thu-do',
	        'type'          =>  'CAU',
	        'numbers'        =>  array_reverse($arr_result), 
		);
		$this->DateLuck->create();
		$this->DateLuck->save($dateLuckData);

	}

	protected function getResultToCalculate()
	{
		$this->mRESULT = array();

		$dateResultsModel = new AppModel();
		$dateResultsModel->useTable = 'date_results';
		$dateSearch = $this->mYear.$this->mMonth.$this->mDay;
		$option = array(
			'order'		=>	array(
				'date'	=>	'DESC'
			),
			'limit'		=>	self::LIMIT_DATE,
			'conditions'	=>	array(
				'region_code'	=>	'thu-do',
				'date' 			=> array(
					'$lte' => (int)$dateSearch,
					//'$lte' => 20160105,
					
				)
			),
		);

		$resultArray = $dateResultsModel->find('all',$option);
		
		if( !empty( $resultArray ) ){
			$n = 1;
			foreach ($resultArray as $item) {

				$item = $item['AppModel'];

				if( $n==1 ){
					$stringDate = substr($item['date'], 0, 4).'-'.substr($item['date'], 4, 2).'-'.substr($item['date'], 6, 2);
					$unixTime = strtotime($stringDate) + 86400;
					$this->mDateToSave = (int)date('Ymd',$unixTime);
				}
				$this->mRESULT[] = $this->arrayFlatten($item['numbers']);
				$n ++;
			}
		}

	}


	/**
    * Lấy ra tất cả các loto về nhiều nháy
    * @author: Ungnv
    * @param: mảng 27 số của bộ kết quả 1 ngày
    * @return: mảng các con lô về nhiều nháy
    */
	protected function getMultiLoto($result)
	{
		$lotoArray = $this->getAllLoto($result);

		$lotoMulti = array_unique( array_diff_assoc( $lotoArray, array_unique( $lotoArray ) ) );

		$arr = array_unique($lotoMulti);
		return array_values($arr);
	}


    public function beforeFilter()
    {
    	parent::beforeFilter();
    	$this->Auth->allow();

    }

    protected function checkNumberCouple($arrayCouple, $number)
	{
		if(!empty($arrayCouple)){
			foreach ($arrayCouple as $item) {
				if( ($item == $number) || (strrev($item) == $number) ){
					return true;
				}
			}
		}
		return false;
	}
}