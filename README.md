# TencentMusicApi
QQ 音乐 API - PHP 版

基于 QQ 音乐 web 端接口改写的 PHP 版本， 建议 PHP 5.6 以上环境
本 API 为个人学习作品，请支持正版音乐，勿滥用

### Function
 - [x] 关键字搜索
 - [x] 歌手热门单曲
 - [x] 歌曲详细信息
 - [x] 专辑解析
 - [x] 歌单解析
 - [x] 歌曲地址获取
 - [x] 歌词解析
 - [ ] MV 解析

### Get Started

```php
# just download the TencentMusicAPI.php into directory, require it with the correct path.

require_once 'TencentMusicAPI.php';

# Initialize
$api = new TencentMusicAPI();

# Get data
$result = $api->search('hello');
// $result = $api->artist('003CoxJh1zFPpx');
// $result = $api->detail('001icUif3vTGcO');
// $result = $api->album('002rBshp4WPAut');
// $result = $api->playlist('801491460');
// $result = $api->url('001icUif3vTGcO');
// $result = $api->lyric('001icUif3vTGcO');

# return JSON, just use it
var_dump(json_decode($result));

```

### Link
 - [METO Blog](https://i-meto.com/)


### License
TencentMusicApi is under the MIT license.
