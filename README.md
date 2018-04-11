## 无人超市开发日志

-------------------------------------------------
2018.04.11 21：02

1. 新增添加商品接口 `/api/market/controller/GoodsController.php`

2. 新增获取商品类别接口 `/api/market/controller/GoodsTypeController.php`

3. 新增获取商品ID接口 `/api/market/controller/Goods/getGoodsId`
	修改ID生成算法，采用大数的加法算法。

4. 新增添加商品ID等完整信息接口 `/api/market/controller/Goods/submit`
	注: 没有检测ID是否会重复!
	
5. 新增获取商品信息接口 `/api/market/controller/Goods/getGoodsInfo`

-------------------------------------------------
2018.04.06 09:37

1. 添加员工客户端登录token表

2. 修改api/admin/controller/PublicController.php
	作为员工登录接口
	
3. 修改/cmf/controller/RestAdminBaseController.php
	作为PublicController的基类。


-------------------------------------------------
2018.04.03 19:48

1. 完成商品管理的主界面。。。

2. 完成添加商品的后台逻辑
	添加商品流程:
	1. 选择商品类别，获取商品ID
	2. 填写商品生产日期和生产批号
	3. 写入标签
	4. 数据写入数据库
	
	商品ID结构:
	 V | NSI |        MD          
	   |     |
	   |     |  DC  | AC |   IC    
	---|-----|------|----|---------
	 2 | 0128|  1  | 2  | 4位|20位
	
	说明:(十进制)
	* V: 2 版本 1位
	* NSI: Ecode128 编码体系 4位
	* DC: 分区码 1位
	* AC: 应用码 2位
	* IC: 二级分类 24位
	* IC::type: 商品分类 4位
	* IC::id  : 商品ID 20位

由于给定的RFIDReader的驱动程序只有dll, 故添加商品只能在windows环境下完成。

另外，JS似乎调用本地程序的方法((new ActiveXObject("wscript.shell")).Run(filePath))似乎不适用，
可以执行本地程序却无法获得需要的返回值。

考虑搭建windows环境下的商品管理服务程序，包括添加商品，添加到购物车，自助收银。
利用JavaFX搭建界面，但是由于给的是动态库，因此后面会用到JNI。。。

换环境啦~linux 再见!

-------------------------------------------------
2018.04.02 16:30

1. 修改了部分数据表
	* 新增购物车表
	* 去掉积分和余额管理
	* 新增店铺管理，自助终端管理，供应管理，商品管理以及销售管理目录
	* 新增以上目录下路由权限

为了能够顺利通过中期检查，先编写商品管理部分(进货)代码，再写销售管理(添加购物车, 售卖收银)。

-------------------------------------------------
2018.04.01 17:51

1. 新增 [thinkcmfapi](https://github.com/thinkcmf/thinkcmfapi)
	覆盖项目根目录，修改 `api/user/controller/PublicController.php`。。。
	还好上传到啦github, 不然README.md被覆盖，日志就丢掉了。
	
2. 基于API完成用户注册，用户登录，用户登出等功能。


3. 完成验证码发送功能
	1. 生成verification_code, 调用`cmf_get_verification_code($account)`
	2. 将verification_code写入数据库, 调用 `cmf_verification_code_log($account, $code, $expireTime = 0)`
	3. 将verification_code发送给用户
		* 邮箱注册用户
			1. 配置好网站的邮箱配置，注意发送用户是发送邮箱名，密码是邮箱授权码。
			2. 安装 配置 `PHPMailer` 发送邮件，直接composer require
			3. 调用 cmf_send_email($account,$subject,$message) 发送邮件
		* 手机注册用户
			直接使用`success()`的时候返回数据。。。因为手机短信验证码收费啊啊啊啊。
			
4. 完成用户注册功能
	1. 使用验证器验证数据是否合法。
	2. 检测用户名和用户帐号是否已经存在。
	3. 检测验证码是否正确。调用`cmf_check_verification_code($account, $code, $clear = false)`
	4. 插入新用户数据，修改`verification_code`表中的验证码。调用`cmf_clear_verification_code($account)`
	5. 返回注册结果
	
5. 完成用户登录功能
	1. 检测数据是否合法
	2. 检测用户状态，是否正常
	3. 匹配密码，利用 `cmf_compare_password()`
	4. 生成token, 利用 `cmf_generate_user_token($userId,$device_Type)`生成。
	5. 更新用户最后登录状态
	6. 返回token以及用户信息

6. 用户登出
7. 用户密码重置

**注: 这里的上传数据使用GET方法和POST方法均可。测试使为简单起使用GET方法。**

-------------------------------------------------
2018.04.01 10:54

1. 编辑管理员新增修改管理员岗位

2. 管理员列表新增删除功能，新增员工状态过滤条件

3. 管理员列表新增管理员离职、启用功能。

到这里，管理员管理就算完成啦。

-------------------------------------------------
2018.03.31 23:30

1. 完成了管理员部分管理: 添加管理员

2. 完善管理员列表，显示员工岗位，增加岗位过滤条件

-------------------------------------------------
2018.03.31 16:52

1. 新建了 `market` 应用模块，使的模块间更加清楚。
	具体如下:
	1.在 app\market 目录下 新建目录 `controller`,`lang`,`validate`, 分别存放 控制器，语言包和验证类。
	2.在 public\themes\admin_simpleboot3 目录下 新建目录 `market`，存放market模块后台管理的View文件。

2. 完成岗位管理(`market\controller\AdminMarketPostController`)，包括岗位列表(`index`)，岗位添加(`add`)，岗位编辑(`edit`)，岗位删除(`delete`)

-------------------------------------------------
2018.03.30

1. 修改员工登录控制
修改登录选择，用户名或者手机号登录;
修改`AdminBaseController.class.php`中的权限检查;

2. 修改后台菜单中无人超市应用的子菜单项的显示选项。

目前，网站可以正常安装并且进入后台。
但是，员工管理、用户管理 以及 无人超市应用均未完成。


-------------------------------------------------
2018.03.29

完成 E-R图到关系数据库的转化

执行顺序：
1. thinkcmf.sql ThinkCMF管理后台基础
2. role.sql 角色管理 和 权限管理
3. market.sql 无人超市全局配置
4. user.sql 部门管理 和 会员管理
5. market_account.sql 无人超市帐务管理
6. market_store.sql 无人超市商店具体管理
7. goods.sql 商品管理
8. sale.sql 交易管理
9. portal.sql 门户网站

共49个数据表
目前，数据库已经成功建立，但是管理网站后台仍然安装失败！

-------------------------------------------------
2018.03.27
搭建ubuntu开发环境，下载ThinkCMF框架，部署到服务器，上传github保存。


