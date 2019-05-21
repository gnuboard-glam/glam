<?php

namespace Glam;

use Dot\File;

class NaverShopProducts
{
	protected $fields = [
		'id' => '',
		'title' => '',

        // 가격
        'price_pc' => '',
        // 정가
        'normal_price' => '',

        // 상품 주소
		'link' => '',

        // 이미지 주소
		'image_link' => '',


        // 카테고리
		'category_name1' => '',
		'category_name2' => '',
		'category_name3' => '',
		'category_name4' => '',

        'model_number' => '',

        'brand' => '',

        // 할인카드명/카드할인가
        // 신한카드^10000|KB국민카드^11000|현대카드^12000,
        // • 띄어쓰기 없이 표기하며, ‘카드명^카드할인가’ 로 구분하여 입력. 여러 카드 행사를 동시 진행 시 ‘|’기호로 구분하여 입력
        // • 할인율이 가장 큰 카드를 먼저 입력합니다.

        // 'card_event' => '',

        // 무이자 할부 이벤트
        // • 삼성카드^2~3|신한카드^2~3|현대카드^2~3
        // • 무이자행사카드명^무이자할부개월수로 입력하며, 여러 카드사의 행사를 동시 진행하는 경우 ‘|’ 기호로 분리하여 입력합니다.
        // • 카드사명은 할인카드명을 따르며, 무이자 할부 행사는 제품 단가 기준으로 제공이 가능한 무이자행사여야 합니다.
        //'interest_free_event' => ''
        // 리뷰 수
        // 'review_count' => 0

        // 'option_detail' => ''
        // 레이스원피스^23000|멜빵원피스^25000|…

        // 배송료
		'shipping' => 0,

        // 배송부가정보
        // 'shipping_settings' => ''
	];

	protected $products = [];

	/**
	 * filename as output
	 * @var string
	 */
	protected $fileName;

	function __construct(string $fileName)
	{
		$this->fileName = $fileName;
	}

	function add(array $product)
	{
	    $keys = array_keys($product);

		$this->products[] = $product;

		return $this;
	}

	function render(){
		$values = [];

		$fields = &$this->fields;
		foreach($this->products as $product){
			$values[] = '<<<begin>>>';

			foreach($fields as $key => $value){
				$value = [];
				$value[] = '<<<' . $key . '>>>';
				$value[] = $product[$key] ?? $value;
				$value = implode('', $value);
				$values[] = $value;
			}

			$values[] = '<<<ftend>>>';
		}

		$values = \implode('\n', $values);
		$values = \iconv('utf-8', 'euc-kr', $values);

		File::write($this->fileName, $values);
	}
}