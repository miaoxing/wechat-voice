<?php

namespace Miaoxing\WechatVoice\Controller;

class WechatVoice extends \miaoxing\plugin\BaseController
{
    public function getWechatVoiceAction($req)
    {
        $validator = wei()->validate(array(
            // 待验证的数据
            'data' => [
                'serverId' => $req['serverId']
            ],
            // 验证规则数组
            'rules' => array(
                'serverId' => [
                    'required' => true
                ],
            ),
            // 数据项名称的数组,用于错误信息提示
            'names' => array(
                'serverId' => '服务Id'
            ),
            'messages' => [
                'serverId' => [
                    'required' => '请输入服务Id',
                ]
            ]
        ));
        if (!$validator->isValid()) {
            $firstMessage = $validator->getFirstMessage();
            return json_encode(array("code" => -7, "message" => $firstMessage));
        }

        $account = wei()->wechatAccount->getCurrentAccount();
        $api = $account->createApiService();
        $url = $api->getMediaUrl($req['serverId']);

        // 如果指定的文件上传服务存在,使用相应服务上传
        $fileService = null;
        if ($req['fileService']) {
            $serviceName = $req['fileService'] . '.file';
            if ($this->wei->getConfig($serviceName) !== false) {
                $fileService = $this->wei->get($serviceName);
            }
        }

        if (!$fileService) {
            $fileService = wei()->file;
        }

        $ret = $fileService->upload($url, 'amr');
        if ($ret['code'] !== 1) {
            wei()->logger->alert('下载语音失败', $ret);
        }

        return $this->ret($ret);
    }

    public function testAction($req)
    {
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?media_id=8camm2I-zFlqLZVqa_Yzw8hLzEsR59PVklY-46acre0zoFjoQwybINYsSnS-ZQYR&access_token=0nRXaB2bjjyCQM5J8TZYktaaz6KrPX211QBUB9F_dSnvDeIs1ybuNEZCKNigIer9RDFGSU-NvLovAl2uehN2KRoZyGjY_M2E25NuiPqLvrrvLsbivYp3SeNNcVHDcz6cPFGeABAZPQ";

        // 如果指定的文件上传服务存在,使用相应服务上传
        $fileService = null;
        if ($req['fileService']) {
            $serviceName = $req['fileService'] . '.file';
            if ($this->wei->getConfig($serviceName) !== false) {
                $fileService = $this->wei->get($serviceName);
            }
        }

        if (!$fileService) {
            $fileService = wei()->file;
        }

        $ret = $fileService->upload($url, 'amr');
        if ($ret['code'] !== 1) {
            wei()->logger->alert('下载语音失败', $ret);
        }

        return $this->ret(['code' => -1, 'message' => $ret]);
    }
}