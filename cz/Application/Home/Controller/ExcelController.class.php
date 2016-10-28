<?php
namespace Home\Controller;

use Think\Controller;

class ExcelController extends Controller
{
    // 导出数据到excel表
    public function index()
    { // excel表头
  
        $headArr[] = '下单时间';
        $headArr[] = '订单号';
        $headArr[] = '用户id';
        $headArr[] = '电话';
        // 数据
        $data = D('User')->join('`order` ON order.uid=user.uid')->where('user.genre=1')->field('order.create_time,order.orderid,user.uid,user.tel')->select();
        if($data){
            foreach ($data as $key => $value) {
                $data[$key]['create_time']=date("Y-m-d H:i:s",$value['create_time']);
            }
        }
        $filename = "goods_list";
        $this->getExcel($filename, $headArr, $data); // $filename excel名称 $headArr excel表头
    }

    // public function import()
    // {
    //     $this->display();
    // }

    // public function upload()
    // {
    //     header("Content-Type:text/html;charset=utf-8");
    //     $upload = new \Think\Upload(); // 实例化上传类
    //     $upload->maxSize = 3145728; // 设置附件上传大小
    //     $upload->exts = array(
    //         'xls',
    //         'xlsx'
    //     ); // 设置附件上传类
    //     $upload->savePath = '/'; // 设置附件上传目录
    //                              // 上传文件
    //     $info = $upload->uploadOne($_FILES['excelData']);
    //     $filename = './Uploads' . $info['savepath'] . $info['savename'];
    //     $exts = $info['ext'];
    //     // print_r($info);exit;
    //     if (! $info) { // 上传错误提示错误信息
    //         $this->error($upload->getError());
    //     } else { // 上传成功
    //         $this->goods_import($filename, $exts);
    //         $this->success('导入成功');
    //     }
    // }

    // public function save_import($data)
    // {
    //     foreach ($data as $key => $val) {
    //         if ($key > 2) {
    //             $datas['id'] = $val['A'];
    //             $datas['tile'] = 'title';
    //             $datas['content'] = $val['D'];
    //             $datas['time'] = date('y-m-d h:i:s', time()); 
    //             D('NewsMongo')->add($datas);
    //         }
    //     }
    // }

    private function getExcel($fileName, $headArr, $data)
    {
        vendor('PHPExcel');
        $date = date("Y_m_d", time());
        $fileName .= "_{$date}.xls";
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        // 设置表头
        $key = ord("A");
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        // print_r($data);exit;
        foreach ($data as $key => $rows) { // 行写入
            $span = ord("A");
            foreach ($rows as $keyName => $value) { // 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j . $column, $value);
                $span ++;
            }
            $column ++;
        }
        
        $fileName = iconv("utf-8", "gb2312", $fileName);
        // 重命名表
        // 设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    protected function goods_import($filename, $exts = 'xls')
    {
        // 导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        vendor('PHPExcel');
        // 创建PHPExcel对象，注意，不能少了\
        $PHPExcel = new \PHPExcel();
        // 如果excel文件后缀名为.xls，导入这个类
        if ($exts == 'xls') {
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } else 
            if ($exts == 'xlsx') {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }
        
        // 载入文件
        $PHPExcel = $PHPReader->load($filename);
        
        // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        // 获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        // 获取总行数
        $allRow = $currentSheet->getHighestRow();
        // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow ++) {
            // 从哪列开始，A表示第一列
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
                // 数据坐标
                $address = $currentColumn . $currentRow;
                // 读取到的数据，保存到数组$arr中
                $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
            }
        }
        $this->save_import($data);
    }
}
?>