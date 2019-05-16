# GLAM

> `G`nu Board draw the `L`ine `A`s `M`odern

그누보드와 영카트의 기능 개선하고 관리자 및 개발상 편의성을 향상 합니다.
단순히 개발자 화면이나 데이터베이스만을 사용하는 기능이 존재합니다.

주요 기능은 아래와 같습니다.

* 계층 제한이 없는 메뉴
* ~~게시물 순서 변경~~

반응형 디자인을 기본으로 `그누보드 모바일`, `Jquery`는 지양 합니다.

## 저작권
글램을 원인으로 발생하는 문제에 대해 책임지지 않습니다.

## 파일 구조
* `adm/`
    * `admin.menu000.glam.php`
* `plugin/`
    * `glam/**/*`
* `extend/`
    * `glam.extend.php`
* `theme`
    * `/glam/**/*`
* `.htaccess`
    
### 설치 방법

1. 글램을 구성하는 모든 파일을 원본에 추가 합니다.
2. 안내에 따라 일부 파일에 내용을 추가하거나 수정 합니다.

#### 파일 수정

##### `/head.sub.php`

```php
<?php Glam\Glam::that()->head()?>
```

> &lt;/head&gt; 이전에 위 코드를 추가 합니다.
> 관리자 화면에서 글램이 CSS, JS 파일을 불러오기 위해 사용 합니다.

##### `/adm/admin.lib.php`

> `v5.4`기준 `457`번 줄

__수정 전__:
```php
if( $p['path'] && ! preg_match( '/\/'.preg_quote(G5_ADMIN_DIR).'\//i', $p['path'] ) ){
    $msg = '올바른 방법으로 이용해 주십시오';
}
```

__수정 후__:
```php
if( $p['path'] && ! preg_match( '/\/'.preg_quote(G5_ADMIN_DIR).'(in)?\//i', $p['path'] ) ){
    $msg = '올바른 방법으로 이용해 주십시오';
}
```

> 글램의 관리자 화면을 정상으로 인식 합니다.

#### 짧은 주소

글램의 자체적인 짧은 주소 기능 지원과 그누보드 5.4 부터 도입된 짧은 주소 설정이 충돌하므로 조율해야 합니다.
> [짧은 주소 설정](md/rewrite-module.md)