<?php
/**
 * 商品管理控制器
 * author: GT 
 * time: 2018-04-2 16:45
 */
 
 
namespace app\market\controller;
 
use cmf\controller\AdminBaseController;
use think\Db;
use think\Validate;
  
class AdminMarketGoodsController extends AdminBaseController {

	/*********************************************************
	  * 商品类管理
	  *********************************************************/
	
	/**
	 * 商品类列表
	 */
	 
	public function indexGoodsType(){
		$where = [];
		//搜索条件
		$name = $this->request->param('name');
		$address = $this->request->param('address');
		$price_min = $this->request->param('price_min');
		$price_max = $this->request->param('price_max');
		$company = $this->request->param('company');
		
		if(!empty($name)){
			$where['name'] = ['LIKE', "%$name%"];
		}
		
		if(!empty($address)){
			$where['address'] = ['LIKE', "%$address%"];
		}
		
		if(isset($price_min) && !empty($price_max)){// 保证 $price_max > 0
			$where['price'] = ['BETWEEN', ["$price_min","$price_max"]];
		}
		
		if(!empty($company)){
			$where['company'] = ['LIKE', "%$company%"];
		}
		
		$goodsType = Db::name('goods_type')->where($where)->paginate(10);
		$arr = ['name'=>"$name", 'address'=>"$address", 'price_min'=>"$price_min",
				'price_max'=>"$price_max",'company'=>"$company"];
		$goodsType->appends($arr);
		
		//获取分页显示
		$pages = $goodsType->render();
		
		$this->assign('page', $pages);
		$this->assign('goodsType', $goodsType);
		
		return $this->fetch();
	}
	
	/**
	 * 添加商品类
	 */
	 public function addGoodsType(){
		return $this->fetch();	 	
	 }
	 
	 /**
	  * 添加商品类提交
	  */
	 public function addGoodsTypePost(){
	 	if($this->request->isPost()){
	 		$data = $this->request->param();
	 		//验证提交的数据
	 		$validate = new Validate([
	 			'name' 		=> "require",
	 			'price' 	=> "require",
	 			'address' 	=> "require",
	 			'company' 	=> "require"
	 		]);
	 		
	 		$validate->message([
	 			'name.require' 	=> "请输入商品名!",
	 			'price.require' => "请输入价格!",
	 			'address.require' 	=> '请输入生产地址!',
	 			'company.require'	=> '请输入生产公司!'
	 		]);
	 		
	 		if(!$validate->check($data)){
	 			$this->error($validate->getError());
	 		}
	 		
	 		$result = Db::name('goods_type')->insertGetId($data);
	 		if($result){
	 			$this->success('添加商品类成功!',url('AdminMarketGoods/indexGoodsType'));
	 		}else{
	 			$this->error("添加商品类失败!");
	 		}
	 	}//isPost()
	 }
	 
	 /**
	  * 编辑商品类别
	  */
	 public function editGoodsType(){
		 $type_id = $this->request->param("id");
		 $type = Db::name('goods_type')->where("id","$type_id")->find();
		 $this->assign("type",$type);
		 return $this->fetch();
	 }
	 
	 /**
	  * 编辑商品类别提交
	  */
	 public function editGoodsTypePost(){
		 if($this->request->isPost()){
			 $data = $this->request->param();
	 		//验证提交的数据
	 		$validate = new Validate([
				'id'		=> "require",
	 			'name' 		=> "require",
	 			'price' 	=> "require",
	 			'address' 	=> "require",
	 			'company' 	=> "require"
	 		]);
	 		
	 		$validate->message([
				'id.require'	=> "请传入商品类别ID",
	 			'name.require' 	=> "请输入商品名!",
	 			'price.require' => "请输入价格!",
	 			'address.require' 	=> '请输入生产地址!',
	 			'company.require'	=> '请输入生产公司!'
	 		]);
	 		
	 		if(!$validate->check($data)){
	 			$this->error($validate->getError());
	 		}
			
			$result = Db::name('goods_type')->update($data);
			if($result){
				$this->success("修改商品类别成功!",url('AdminMarketGoods/indexGoodsType'));
			}else{
				$this->error("修改商品类别失败!");
			}
		 }
	 }
	 
	 /**
	  * 删除商品类别
	  */
	 public function deleteGoodsType(){
		 $type_id = $this->request->param('id');
		 if(empty($type_id)){
			 $this->error("请传入商品类别ID!");
		 }
		 $count = Db::name('goods')->where('type_id',"$type_id")->count();
		 if($count == 0){
			 $result = Db::name('goods_type')->where("id","$type_id")->delete();
			 if($result){
				 $this->success("删除商品类别成功!");
			 }else{
				 $this->error("删除商品类别失败!");
			 }
		 }else{
			 $this->error("该类别下含有商品，不可删除!");
		 }
	 }
	 
	 /*********************************************************
	  * 商品管理
	  *********************************************************/
	  
	  
	/**
	 * 商品列表
	 */
	public function indexGoods(){
		$where = [];
		/*搜索条件*/
		$name = $this->request->param('name');
		$keywords = $this->request->param('keywords');
		$date_min = $this->request->param("date_min");
		$date_max = $this->request->param('date_max');
		$batch_number = $this->request->param('batch_number');
		$status = $this->request->param('status');
		
		if(!empty($name)){
			$where['b.name'] = ['LIKE', "%$name%"];
		}
		
		if(!empty($keywords)){
			$where['b.address|b.company'] = ['LIKE', "%$keywords%"];			
		}
		
		if(!empty($date_min) && !empty($date_max)){
			$where['a.manufacture_date'] = ['between', ["$date_min","$date_max"]];
		}
		
		if(!empty($batch_number)){
			$where['a.batch_number'] = ['=', "$batch_number"];
		}
		
		if(!empty($status)){
			$where['a.status'] = ['=', "$status"];
		}
		
		$goods = Db::name('goods')
					->alias('a')
					->field("a.*, b.name, b.images, b.price")
					->join('__GOODS_TYPE__ b', 'a.type_id = b.id')
					->where($where)
					->order('manufacture_date desc')
					->paginate(10);
					
		$arr = [
			'name' 			=> "$name",
			'keywords' 		=> "$keywords",
			'date_min'		=> "$date_min",
			'date_max'		=> "$date_max",
			'batch_number'		=> "$batch_number",
			'status'		=> "$status",
		];
		$goods->appends($arr);
		
		//获取分页显示
		$pages = $goods->render();
		
		$this->assign('page', $pages);
		$this->assign('goods', $goods);
		
		return $this->fetch();
		
	}
	
	/**
	 * 添加商品
	 */
	public function addGoods(){
		$goodsType = Db::name('goods_type')->select();
		$this->assign('goodsType', $goodsType);
		
		return $this->fetch();
	}
	
	/**
	 * 获取商品ID
	 * 商品id格式
	 * |---------------------------------------|
	 * |   |     |            MD               |
	 * | V | NSI |-----------------------------|
	 * |   |     | DC | AC |     IC            |
	 * |---------------------------------------|
	 * | 2 |0128 | 1  | 01  |(4分类)|20(商品id) |
	 * |---------------------------------------|
	 */
	public function getGoodsId(){
		if($this->request->isAjax() 
			&& $this->request->isPost()){
			$goodsType = $this->request->param('goodsType');
			if(empty($goodsType)){
				$this->error("请选择商品类别!");
			}else{
				$goods_id = Db::name('goods')
							->where('type_id',"$goodsType")
							->limit(1)
							->order("id DESC")
							->column('id');
				$id = "20128101";//v:2 NSI:0128 DC:1 AC:01
				$id .= $this->goodsType2Str($goodsType);
				if(empty($goods_id)){//还没有该类型商品
					$id .= $this->generateGoodsId2('00000000000000000000');
				}else{
					$goods_id = $goods_id['0'];
					$id_last =substr($goods_id,-20);//从结尾开始截取20位
					$id .= $this->generateGoodsId2($id_last);
				}
				$this->success("获取成功!",'',['goods_id'=>$id]);
			}
		}else{
			$this->error("获取方式不正确!");
		}
	}
	
	/**
	 * 将商品类别id转化成4位的字符串
	 */
	private function goodsType2Str($goodsType){
		$str = strval($goodsType);
		$str = sprintf("%04s", $str);//字符串自动补全
		return $str;
	}
	
	/**
	 * 产生商品ID
	 */
	private function generateGoodsId($now){
		$now_id = intval($now);
		$id = $now_id+1;
		$str = strval($id);
		$str = sprintf("%020s",$str);
		return $str;
	}
	
	private function generateGoodsId2(string $now){
		$b = "00000000000000000001";
		$len = 20;
		$c = 0;// 进位
		$result  = '';// 结果
		while($len--){
			$t1 = $now[$len];
			$t2 = $b[$len];
			$t = $t1 +$t2 + $c;
			$c = intval($t)/10;
			$result = ($t%10) . $result;
		}
		return $result;
	}
	
	/**
	 * 商品下架
	 * @param id 商品id
	 */
	public function deleteGoods(){
		$goods_id = $this->request->param('id');
		if(!empty($goods_id)){
			$status = Db::name("goods")->where('id',"$goods_id")->limit(1)->column('status');
			if(!empty($status)){
				$status = $status['0'];
				if($status == 1){
					$result = Db::name('goods')->where('id',"$goods_id")->delete();
					if($result){
						$this->success("下架成功!");
					}else{
						$this->error("下架失败!");
					}
				}else{//已售
					$this->error("该商品已售 或 被锁定，不可下架!");
				}
			}else{
				$this->error("商品不存在!");
			}
		}else{
			$this->error("请传入商品ID!");
		}
	}
	
	/**
	 * 编辑商品
	 */
	public function editGoods(){
		$goods_id = $this->request->param('id');
		$goods = Db::name('goods')->where('id',"$goods_id")->find();
		if($goods['status'] != 1){
			return $this->error("商品已售或被锁定，不可修改!");
		}
		$goods_type = Db::name('goods_type')->select();
		$this->assign("types", $goods_type);
		$this->assign("goods", $goods);
		return $this->fetch();
	}
	
	/**
	 * 编辑商品提交
	 */
	public function editGoodsPost(){
		if($this->request->isPost()){
			$validate = new Validate([
				'id' 			=> "require",
				'type_id'		=> "require",
				"manufacture_date"	=> "require",
				"batch_number"	=> "require",
			]);
			$validate->message([
				"id.require"			=> "请指定商品ID",
				"type_id.require"		=> "请指定商品类别",
				"manufacture_date.require"	=> "请输入生产日期",
				"batch_number.require"	=> "请输入生产批号"		
			]);
			$data = $this->request->param();
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			$data['manufacture_date'] = strtotime($data['manufacture_date']);
			if(array_key_exists("status",$data)){
				$data['status'] = 1;
			}
			
			$result = Db::name('goods')->update($data);
			if($result){
				$this->success("修改成功!", url('AdminMarketGoods/indexGoods'));
			}else{
				$this->error("修改失败!");
			}
		}
	}
}
