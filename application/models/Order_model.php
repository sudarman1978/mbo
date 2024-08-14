<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{
    protected $dbDms;
    protected $dbwms;

    protected $_table = '`tbl_bth`';
    protected $_table_detail = '`sales_order_details`';

    public function __construct ()
    {
        parent::__construct();
        $this->dbDms = $this->load->database('dms', TRUE);
        $this->dbwms = $this->load->database('dnxwms', TRUE);

    }

    public function getAll ()
    {
        $order = $this->dbDms->get($this->_table)->result();

        $orders = [];
        foreach ($order as $row) {
            $detail = [];

            $this->dbDms->where('so_id', $row->id);
            $details = $this->dbDms->get($this->_table_detail)->result();

            if ($details) {
                foreach ($details as $det) {
                    $detail[] = [
                        'item_no' => $det->item_no,
                        'description' => $det->description,
                        'qty' => $det->qty,
                    ];
                }
            }

            $orders[] = [
                'document_no' => $row->document_no,
                'document_date' => $row->document_date,
                'customer' => $row->customer,
                'address' => $row->address,
                'detail' => $detail,
            ];
        }

        return $orders;
    }

    public function crawl ()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.vstecsindoapps.com/DUNEX-API/10237890.aspx?Pass=skjAklklfSFdfgg7sF90LlJGYsdffLJOL7687",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        
        $data = json_decode($json, TRUE);
        $year_trans = date("y");
        $date_trans = date("ym");

        $mkautono = $this->dbwms->query("select t0.fld_btno  from tbl_bth t0
         where t0.fld_bttyid='103' and t0.fld_baido = '1' order by t0.fld_btid desc limit 1");
        $lbacd = $mkautono->row();
   
        foreach ($data['Picklist']['SalesOrder'] as $row) {
            $get_seq_number = (substr($lbacd, 13, 5) + 1);
            $seq_number = str_pad($get_seq_number, 5, "0", STR_PAD_LEFT);
            $auto = 'DET' . "/" . "OUT" . "/" . $date_trans . "/" . $seq_number;
            $bttyid = 103;
            $baido = 1;
            $baidc = 5078;
            
            $order = [
                'fld_btnoreff' => $row['DocumentNo'],
                'fld_baidc' => $baidc,
                'fld_baido' => $baido,
                'fld_bttyid' => $bttyid,
                'fld_btno' => $auto,
                'fld_btdt' => date('Y-m-d', strtotime($row['DocumentDate'])),
                'fld_btp05' => $row['Customer'],
                'fld_btp06' => $row['Address'],
            ];
            
            $cekdong = $this->dbwms->query("Select fld_btnoreff 'doc',count(1) 'count' from tbl_bth where fld_btnoreff = '".$row['DocumentNo']."' group by fld_btnoreff, fld_btdt");
            $rowcek = $cekdong->row();
           
            if($rowcek->count > 0){
                echo "udah ada dong";
            }else{
                $this->dbwms->insert($this->_table, $order);

            }

            $id = $this->dbwms->insert_id();
    /*
            if (isset($row['Detail'])) {
                if ($row['Detail']['ItemNo']) {
                    $details = [
                        'so_id' => $id,
                        'item_no' => $row['Detail']['ItemNo'],
                        'description' => $row['Detail']['Description'],
                        'qty' => $row['Detail']['Qty'],
                    ];


                    $this->dbDms->insert($this->_table_detail, $details);
                } else {
                    foreach ($row['Detail'] as $k => $v) {
                        $details = [
                            'so_id' => $id,
                            'item_no' => $row['Detail'][$k]['ItemNo'],
                            'description' => $row['Detail'][$k]['Description'],
                            'qty' => $row['Detail'][$k]['Qty'],
                        ];

                        $this->dbDms->insert($this->_table_detail, $details);
                    }
                }
            }*/
        }

        echo 'Your data has been extracted successfully';
        return $auto;

    }

    public function insert ($data)
    {
        $data = [
            'event' => $data['event'],
            'ip' => $data['ip'],
            'link' => $data['link'],
            'user_id' => $data['user_id'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert($this->_table, $data);

        return $data;
    }

    public function getLast ()
    {
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        
        return $this->db->get($this->_table)->row();
    }
}
