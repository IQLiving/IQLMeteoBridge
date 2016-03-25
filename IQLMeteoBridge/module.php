<?
class IQLMeteoBridge extends IPSModule {

    public function Create() {
        //Never delete this line!
        parent::Create();
        //These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
        $this->RegisterPropertyString("sensortype","");
        $this->RegisterPropertyInteger("sensorid",0);
    }

    public function ApplyChanges() {
        //Never delete this line!
        parent::ApplyChanges();

        $this->RegisterProfileInteger("Solar.IQLMB","",""," W/qm",0,0,1);
        $this->RegisterProfileIntegerEx("WindDirText.IQLMB", "", "", "", Array(
            Array(0, "N", "", -1),
            Array(23, "NNO",  "",-1),
            Array(45, "NO",  "",-1),
            Array(68, "ONO",  "",-1),
            Array(90, "O",  "",-1),
            Array(113, "OSO",  "",-1),
            Array(135, "SO",  "",-1),
            Array(158, "SSO",  "",-1),
            Array(180, "S",  "",-1),
            Array(203, "SSW",  "",-1),
            Array(225, "SW",  "",-1),
            Array(248, "WSW",  "",-1),
            Array(270, "W",  "",-1),
            Array(293, "WNW",  "",-1),
            Array(315, "NW",  "",-1),
            Array(338, "NNW",  "",-1)
        ));
        $this->RegisterProfileInteger("Bodenfeuchte.IQLMB","",""," cb",0,200,1);
        if($this->ReadPropertyString("sensortype") == "THB") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableFloat("TEMPERATURE","Temperatur","~Temperature",0);
            $this->RegisterVariableInteger("HUMIDITY","Luftfeuchtigkeit","~Humidity",0);
            $this->RegisterVariableFloat("DEWPOINT","Taupunkt","~Temperature",0);
            $this->RegisterVariableFloat("PRESS","Luftdruck","~AirPressure.F",0);
            $this->RegisterVariableFloat("SEAPRESS","Luftdruck Normalnull","~AirPressure.F",0);
            $this->RegisterVariableBoolean("LOWBAT", "Batterie","~Alert",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "RAIN") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableFloat("RAIN","Niederschlag pro Stunde","~Rainfall",0);
            $this->RegisterVariableFloat("TOTAL","Niederschlag Gesamt","~Rainfall",0);
            $this->RegisterVariableFloat("DELTA","Delta","~Rainfall",0);
            $this->RegisterVariableBoolean("LOWBAT", "Batterie","~Alert",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "UV") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableFloat("INDEX", "UV-Index", "",0);
            $this->RegisterVariableBoolean("LOWBAT", "Batterie","~Alert",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "TH") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableFloat("TEMPERATURE","Temperatur","~Temperature",0);
            $this->RegisterVariableInteger("HUMIDITY","Luftfeuchtigkeit","~Humidity",0);
            $this->RegisterVariableFloat("DEWPOINT","Taupunkt","~Temperature",0);
            $this->RegisterVariableBoolean("LOWBAT", "Batterie","~Alert",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "WIND") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableInteger("DIRECTION","Windrichtung","WindDirText.IQLMB",0);
            $this->RegisterVariableFloat("GUST","Windgeschwindigkeit", "~WindSpeed.ms",0);
            $this->RegisterVariableFloat("WIND","Durchschnittswindgeschwindigkeit", "~WindSpeed.ms",0);
            $this->RegisterVariableFloat("GUSTKM","Windgeschwindigkeit","~WindSpeed.kmh",0);
            $this->RegisterVariableFloat("WINDKM", "Durchschnittswindgeschwindigkeit","~WindSpeed.kmh",0);
            $this->RegisterVariableFloat("CHILL","Gefühlte Temperatur", "~Temperature",0);
            $this->RegisterVariableBoolean("LOWBAT", "Batterie","~Alert",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "SOL") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableInteger("RAD","Sonnenstrahlung","Solar.IQLMB",0);
            $this->RegisterVariableBoolean("LOWBAT", "Batterie","~Alert",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "LEAF") {
            $this->RegisterVariableBoolean("LOWBAT", "Batterie","~Alert",0);
            $this->RegisterVariableFloat("TEMPERATURE","Temperatur","~Temperature",0);
            $this->RegisterVariableInteger("HUMIDITY","Blattfeuchte","",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "SOIL") {
            $this->RegisterVariableBoolean("LOWBAT", "Batterie","~Alert",0);
            $this->RegisterVariableFloat("TEMPERATURE","Temperatur","~Temperature",0);
            $this->RegisterVariableInteger("HUMIDITY","Bodenfeute","Bodenfeuchte.IQLMB",0);
        }

        $this->ConnectParent("{B3B1D424-87A5-4F26-93C3-E49BF48873F9}");
    }

    public function ReceiveData($JSONString) {
        $data = json_decode($JSONString);
        if($this->ReadPropertyString("sensortype") == "THB") {
            if(array_key_exists("THB",$data->Buffer)) {
                $sensortype = $this->ReadPropertyString("sensortype");
                $sensorid = (string)"thb" . $this->ReadPropertyInteger("sensorid");
                SetValue($this->GetIDForIdent("ID"), $data->Buffer->$sensortype->$sensorid->id);
                SetValue($this->GetIDForIdent("TEMPERATURE"), $data->Buffer->$sensortype->$sensorid->temp);
                SetValue($this->GetIDForIdent("HUMIDITY"), $data->Buffer->$sensortype->$sensorid->hum);
                SetValue($this->GetIDForIdent("DEWPOINT"), $data->Buffer->$sensortype->$sensorid->dew);
                SetValue($this->GetIDForIdent("PRESS"), $data->Buffer->$sensortype->$sensorid->press);
                SetValue($this->GetIDForIdent("SEAPRESS"), $data->Buffer->$sensortype->$sensorid->seapress);
                $lowbat = (bool) $data->Buffer->$sensortype->$sensorid->lowbat;
                SetValue($this->GetIDForIdent("LOWBAT"),$lowbat);
            }
        }
        elseif($this->ReadPropertyString("sensortype") == "RAIN") {
            if(array_key_exists("RAIN",$data->Buffer)) {
                $sensortype = $this->ReadPropertyString("sensortype");
                $sensorid = (string)"rain" . $this->ReadPropertyInteger("sensorid");
                SetValue($this->GetIDForIdent("ID"), $data->Buffer->$sensortype->$sensorid->id);
                SetValue($this->GetIDForIdent("RAIN"), $data->Buffer->$sensortype->$sensorid->rate);
                SetValue($this->GetIDForIdent("TOTAL"), $data->Buffer->$sensortype->$sensorid->total);
                SetValue($this->GetIDForIdent("DELTA"), $data->Buffer->$sensortype->$sensorid->delta);
                $lowbat = (bool) $data->Buffer->$sensortype->$sensorid->lowbat;
                SetValue($this->GetIDForIdent("LOWBAT"),$lowbat);
            }
        }
        elseif($this->ReadPropertyString("sensortype") == "UV") {
            if(array_key_exists("UV",$data->Buffer)) {
                $sensortype = $this->ReadPropertyString("sensortype");
                $sensorid = (string)"uv" . $this->ReadPropertyInteger("sensorid");
                SetValue($this->GetIDForIdent("ID"), $data->Buffer->$sensortype->$sensorid->id);
                SetValue($this->GetIDForIdent("INDEX"), $data->Buffer->$sensortype->$sensorid->index);
                $lowbat = (bool) $data->Buffer->$sensortype->$sensorid->lowbat;
                SetValue($this->GetIDForIdent("LOWBAT"),$lowbat);
            }
        }
        elseif($this->ReadPropertyString("sensortype") == "TH") {
            if(array_key_exists("TH",$data->Buffer)) {
                $sensortype = $this->ReadPropertyString("sensortype");
                $sensorid = (string)"th" . $this->ReadPropertyInteger("sensorid");
                SetValue($this->GetIDForIdent("ID"), $data->Buffer->$sensortype->$sensorid->id);
                SetValue($this->GetIDForIdent("TEMPERATURE"), $data->Buffer->$sensortype->$sensorid->temp);
                SetValue($this->GetIDForIdent("HUMIDITY"), $data->Buffer->$sensortype->$sensorid->hum);
                SetValue($this->GetIDForIdent("DEWPOINT"), $data->Buffer->$sensortype->$sensorid->dew);
                $lowbat = (bool) $data->Buffer->$sensortype->$sensorid->lowbat;
                SetValue($this->GetIDForIdent("LOWBAT"),$lowbat);
            }
        }
        elseif($this->ReadPropertyString("sensortype") == "WIND") {
            if(array_key_exists("WIND",$data->Buffer)) {
                $sensortype = $this->ReadPropertyString("sensortype");
                $sensorid = (string)"wind" . $this->ReadPropertyInteger("sensorid");
                $gustkm = $data->Buffer->$sensortype->$sensorid->gust * 3600 / 1000;
                $windkm = $data->Buffer->$sensortype->$sensorid->wind * 3600 / 1000;
                SetValue($this->GetIDForIdent("ID"), $data->Buffer->$sensortype->$sensorid->id);
                SetValue($this->GetIDForIdent("DIRECTION"), $data->Buffer->$sensortype->$sensorid->dir);
                SetValue($this->GetIDForIdent("GUST"), $data->Buffer->$sensortype->$sensorid->gust);
                SetValue($this->GetIDForIdent("GUSTKM"), $gustkm);
                SetValue($this->GetIDForIdent("WIND"), $data->Buffer->$sensortype->$sensorid->wind);
                SetValue($this->GetIDForIdent("WINDKM"), $windkm);
                SetValue($this->GetIDForIdent("CHILL"), $data->Buffer->$sensortype->$sensorid->chill);
                $lowbat = (bool) $data->Buffer->$sensortype->$sensorid->lowbat;
                SetValue($this->GetIDForIdent("LOWBAT"),$lowbat);
            }
        }
        elseif($this->ReadPropertyString("sensortype") == "SOL") {
            if(array_key_exists("SOL",$data->Buffer)) {
                $sensortype = $this->ReadPropertyString("sensortype");
                $sensorid = (string)"sol" . $this->ReadPropertyInteger("sensorid");
                SetValue($this->GetIDForIdent("ID"), $data->Buffer->$sensortype->$sensorid->id);
                SetValue($this->GetIDForIdent("RAD"), $data->Buffer->$sensortype->$sensorid->rad);
                $lowbat = (bool) $data->Buffer->$sensortype->$sensorid->lowbat;
                SetValue($this->GetIDForIdent("LOWBAT"),$lowbat);
            }
        }
        elseif($this->ReadPropertyString("sensortype") == "LEAF") {
            if(array_key_exists("LEAF",$data->Buffer)) {
                $sensortype = $this->ReadPropertyString("sensortype");
                $sensorid = (string)"th" . $this->ReadPropertyInteger("sensorid");
                $lowbat = (bool) $data->Buffer->$sensortype->$sensorid->lowbat;
                SetValue($this->GetIDForIdent("LOWBAT"),$lowbat);
                SetValue($this->GetIDForIdent("TEMPERATURE"), $data->Buffer->$sensortype->$sensorid->temp);
                SetValue($this->GetIDForIdent("HUMIDITY"), $data->Buffer->$sensortype->$sensorid->hum);
            }
        }
        elseif($this->ReadPropertyString("sensortype") =="SOIL") {
            if(array_key_exists("SOIL",$data->Buffer)) {
                $sensortype = $this->ReadPropertyString("sensortype");
                $sensorid = (string)"th" . $this->ReadPropertyInteger("sensorid");
                $lowbat = (bool) $data->Buffer->$sensortype->$sensorid->lowbat;
                SetValue($this->GetIDForIdent("LOWBAT"),$lowbat);
                SetValue($this->GetIDForIdent("TEMPERATURE"), $data->Buffer->$sensortype->$sensorid->temp);
                SetValue($this->GetIDForIdent("HUMIDITY"), $data->Buffer->$sensortype->$sensorid->hum);
            }
        }
    }
    //Remove on next Symcon update
    protected function RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize) {

        if(!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, 1);
        } else {
            $profile = IPS_GetVariableProfile($Name);
            if($profile['ProfileType'] != 1)
                throw new Exception("Variable profile type does not match for profile ".$Name);
        }

        IPS_SetVariableProfileIcon($Name, $Icon);
        IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
        IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);

    }

    protected function RegisterProfileIntegerEx($Name, $Icon, $Prefix, $Suffix, $Associations) {
        if ( sizeof($Associations) === 0 ){
            $MinValue = 0;
            $MaxValue = 0;
        } else {
            $MinValue = $Associations[0][0];
            $MaxValue = $Associations[sizeof($Associations)-1][0];
        }

        $this->RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, 0);

        foreach($Associations as $Association) {
            IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
        }

    }
}