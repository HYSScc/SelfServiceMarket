<?php
/**
 * 支付宝预下单接口测试
 * author: GT
 * time: 2018.04.23 09:16
 */

$arr = [
	"out_trade_no" => "20180424093428947035"
];

$url = "http://localhost:8888/api/market/Goods_Sale/alipay_qrpay";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($arr));
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$data = curl_exec($ch);
echo curl_error($ch);
curl_close($ch);
echo $data;
?>