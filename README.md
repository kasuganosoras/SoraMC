# SoraMC-Panel
<center>
   <img src="http://ww4.sinaimg.cn/large/0060lm7Tly1fnfh8tr8txj30wl0rs7t8.jpg">
</center>
SoraMC Minecraft Server Panel<br>
这是一个 Minecraft 服务器管理面板<br>
支持跨平台，多 Daemon 管理，支持群组服务器<br>
SoraMC 可以说是 PHPMC 3 的续命版本，PHPMC 3 因为开源协议问题已经停更。<br>
这次，改个名字，重新开始搞个大新闻<br>
<b style="color:#FF0000">注意！此面板目前还是实验性的，存在许多问题，请谨慎使用！</b><br>
目前还有几个页面没有完成，还在持续更新中...<br>
吐槽一下...当初设计的时候，是考虑模块化设计的...<br>
结果呢，写代码的时候开了一首 Sky http://music.163.com/#/m/song?id=29553031&userid=469516848<br>
所有的设计理念都在电音和抖腿中成为浮云......<br>
所以你会看到 Loader.php 足足有 43KB ...不要打我...<br>
反正就是所有东西都往里面塞...<br>
本项目对应的 Daemon：https://github.com/kasuganosoras/SoraMC-Daemon<br>
<br>
<h2>测试地址</h2>
地址：http://www.kasuganosora.cn/SoraMC/<br>
账户：testuser<br>
密码：123456<br>
权限：查看、开启、关闭服务器、发送命令<br>
<h2>功能介绍</h2>
<ul>
  <li>可视化的 Minecraft 服务器管理</li>
  <li>可扩展的丰富 API</li>
  <li>完全开放源代码，可在遵守开源协议的前提下自由修改使用</li>
  <li>一个 Panel 可以控制多个 Daemon，实现管理多个服务器</li>
  <li>Panel 与 Daemon 使用 Socket 通讯</li>
  <li>Panel 与 Daemon 的连接是使用 aes_256_cfb 加密的，可自定义加密密钥</li>
  <li>支持多用户，可以创建多个子用户用于管理服务器</li>
  <li>用户权限分级模式，拥有不同权限的用户可以管理不同的 Daemon，查看不同的页面</li>
  <li>支持自定义模板，创建属于你自己的面板</li>
  <li>多种实用工具，方便快捷</li>
</ul>
<h2>界面效果</h2>
<center>
   <img src="http://ww1.sinaimg.cn/large/0060lm7Tly1fnfhw8iv9lj311y0hyjs7.jpg"><br>
   <img src="http://ww3.sinaimg.cn/large/0060lm7Tly1fnfhw8jfd0j311y0hydgr.jpg"><br>
   <img src="http://ww2.sinaimg.cn/large/0060lm7Tly1fnfhw8jkomj311y0hw75t.jpg"><br>
   <img src="http://ww1.sinaimg.cn/large/0060lm7Tly1fnfhw8j21hj311y0hyab1.jpg"><br>
   <img src="http://ww2.sinaimg.cn/large/0060lm7Tly1fnfhw8jsg2j311y0hy3zq.jpg"><br>
   <img src="http://ww1.sinaimg.cn/large/0060lm7Tly1fnfhw8kt0pj311y0hxt9o.jpg"><br>
</center>
<h2>更新记录</h2>
2018.1.05 开工<br>
2018.1.06 设计基础通讯<br>
2018.1.08 实现通讯加密<br>
2018.1.09 完成基础开服、关服、日志获取功能<br>
2018.1.10 实现多线程<br>
2018.1.11 实现多用户<br>
2018.1.12 实现用户权限分级<br>
2018.1.13 实现单 Panel 多 Daemon<br>
2018.1.14 开源到 Github<br>
<br>
未完待更...<br>
<br>
<h2>了解更多</h2>
官方QQ交流群：602945616<br>
感谢支持。
