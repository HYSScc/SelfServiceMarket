## 无人超市开发日志

-------------------------------------------------
2018.03.30

1. 修改员工登录控制
修改登录选择，用户名或者手机号登录;
修改AdminBaseController.class.php中的权限检查;

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


