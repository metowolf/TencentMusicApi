<?php
/*!
 * Tencent(QQ) Music Api
 * https://i-meto.com
 * Version 20160922
 *
 * Copyright 2016, METO
 * Released under the MIT license
 */

class TencentMusicAPI{

    // General
    protected $_USERAGENT='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.30 Safari/537.36';
    protected $_COOKIE='qqmusic_uin=12345678; qqmusic_key=12345678; qqmusic_fromtag=30; ts_last=y.qq.com/portal/player.html;';
    protected $_REFERER='http://y.qq.com/portal/player.html';
    protected $_GUID;

    public function __construct(){
        $this->_GUID=time();
        $data=$this->curl('http://base.music.qq.com/fcgi-bin/fcg_musicexpress.fcg?json=3&guid='.$this->_GUID);
        $this->_KEY=json_decode(substr($data,13,-2),1)['key'];
    }

    // CURL
    protected function curl($url,$data=null){
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        if($data){
            if(is_array($data))$data=http_build_query($data);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
            curl_setopt($curl,CURLOPT_POST,1);
        }
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl,CURLOPT_REFERER,$this->_REFERER);
        curl_setopt($curl,CURLOPT_COOKIE,$this->_COOKIE);
        curl_setopt($curl,CURLOPT_USERAGENT,$this->_USERAGENT);
        $result=curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    // main function
    public function search($s,$limit=30,$offset=0,$type=1){
        $url='http://c.y.qq.com/soso/fcgi-bin/search_cp?';
        $data=array(
            'p'=>$offset+1,
            'n'=>$limit,
            'w'=>$s,
            'aggr'=>1,
            'lossless'=>1,
            'cr'=>1,
        );
        return substr($this->curl($url.http_build_query($data)),9,-1);
    }
    public function artist($artist_mid){
        $url='http://c.y.qq.com/v8/fcg-bin/fcg_v8_singer_track_cp.fcg?';
        $data=array(
            'singermid'=>$artist_mid,
            'order'=>'listen',
            'begin'=>0,
            'num'=>30,
        );
        return substr($this->curl($url.http_build_query($data)),0,-1);
    }
    public function album($album_mid){
        $url='http://c.y.qq.com/v8/fcg-bin/fcg_v8_album_info_cp.fcg?';
        $data=array(
            'albummid'=>$album_mid,
        );
        return substr($this->curl($url.http_build_query($data)),1);
    }
    public function detail($song_mid){
        $url='http://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg?';
        $data=array(
            'songmid'=>$song_mid,
            'format'=>'json',
        );
        return $this->curl($url.http_build_query($data));
    }
    public function url($song_mid){
        $url='http://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg?';
        $data=array(
            'songmid'=>$song_mid,
            'format'=>'json',
        );
        $data=$this->curl($url.http_build_query($data));
        $data=json_decode($data,1)['data'][0]['file'];
        $type=array(
            'size_320mp3'=>array('M800','mp3'),
            'size_128mp3'=>array('M500','mp3'),
            'size_96aac'=>array('C400','m4a'),
            'size_48aac'=>array('C200','m4a'),
        );
        $url=array();
        foreach($type as $key=>$vo){
            if($data[$key])$url[substr($key,5)]='http://dl.stream.qqmusic.qq.com/'.$vo[0].$data['media_mid'].'.'.$vo[1].
                '?vkey='.$this->_KEY.'&guid='.$this->_GUID;
        }
        return json_encode($url);
    }
    public function playlist($playlist_id){
        $url='http://c.y.qq.com/qzone/fcg-bin/fcg_ucc_getcdinfo_byids_cp.fcg?';
        $data=array(
            'disstid'=>$playlist_id,
            'utf8'=>1,
            'type'=>1,
        );
        return substr($this->curl($url.http_build_query($data)),13,-1);
    }
    public function lyric($song_mid){
        $url='http://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric.fcg?';
        $data=array(
            'songmid'=>$song_mid,
            'nobase64'=>'1',
        );
        return substr($this->curl($url.http_build_query($data)),18,-1);
    }

}
