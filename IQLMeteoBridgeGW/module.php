<?
class IQLMeteoBridgeGW extends IPSModule {

    public function Create() {
        //Never delete this line!
        parent::Create();
        //These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
        $this->RegisterPropertyString("ipaddr","");
        $this->RegisterPropertyInteger("port","80");
        $this->RegisterPropertyString("username","");
        $this->RegisterPropertyString("password","");
        $this->RegisterPropertyInteger("timerinterval",0);
        $this->RegisterTimer("UpdateTimer",0,"IQLMBGW_Update(\$_IPS['TARGET']);");
    }

    public function ApplyChanges() {
        //Never delete this line!
        parent::ApplyChanges();
        $this->SetStatus(102);
        if($this->ReadPropertyString("ipaddr") == "") {
            $this->SetStatus(201);
            return;
        }
        elseif($this->ReadPropertyString("username") == "") {
            $this->SetStatus(202);
            return;
        }
        elseif($this->ReadPropertyString("password") == "") {
            $this->SetStatus(203);
            return;
        }
        $this->SetTimerInterval("UpdateTimer",$this->ReadPropertyInteger("timerinterval")*60*1000);
    }

    public function Update() {
        $url = 'http://' .$this->ReadPropertyString("username") .':' .$this->ReadPropertyString("password") .'@' .$this->ReadPropertyString("ipaddr") .':' .$this->ReadPropertyInteger("port") .'/cgi-bin/livedataxml.cgi';
        $xmlResult = new SimpleXMLElement(file_get_contents($url));
        $export = array();
        foreach($xmlResult as $key => $entry) {
            $elementid = (string) $entry['id'];
            $array = (array) $entry;
            foreach($array as $newentry) {
                $export[$key][$elementid] = $newentry;
            }
        }
        $jsonexport = json_encode(Array("DataID" => "{5277C676-F57C-4ECE-B9E3-E276D341FBC4}", "Buffer" => $export));
        $this->SendDataToChildren($jsonexport);
    }
}
