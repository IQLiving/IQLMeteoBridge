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

        if($this->ReadPropertyString("sensortype") == "THB") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableFloat("TEMPERATURE","Temperatur","~Temperature",0);
            $this->RegisterVariableInteger("HUMIDITY","Luftfeuchtigkeit","~Humidity",0);
            $this->RegisterVariableFloat("DEWPOINT","Taupunkt","~Temperature",0);
            $this->RegisterVariableFloat("PRESS","Luftdruck","~AirPressure.F",0);
            $this->RegisterVariableFloat("SEAPRESS","Luftdruck Normalnull","~AirPressure.F",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "RAIN") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableFloat("RAIN","Niederschlag pro Stunde","~Rainfall",0);
            $this->RegisterVariableFloat("TOTAL","Niederschlag Gesamt","~Rainfall",0);
            $this->RegisterVariableFloat("DELTA","Delta","~Rainfall",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "UV") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableFloat("INDEX", "UV-Index", "",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "TH") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableFloat("TEMPERATURE","Temperatur","~Temperature",0);
            $this->RegisterVariableInteger("HUMIDITY","Luftfeuchtigkeit","~Humidity",0);
            $this->RegisterVariableFloat("DEWPOINT","Taupunkt","~Temperature",0);
        }
        elseif($this->ReadPropertyString("sensortype") == "WIND") {
            $this->RegisterVariableString("ID","SensorID","~String",0);
            $this->RegisterVariableInteger("DIRECTION","Windrichtung","~WindDirection",0);
            $this->RegisterVariableFloat("GUST","Windgeschwindigkeit", "~WindSpeed.ms",0);
            $this->RegisterVariableFloat("WIND","Durchschnittswindgeschwindigkeit", "~WindSpeed.ms",0);
            $this->RegisterVariableFloat("CHILL","Gefühlte Temperatur", "~Temperature",0);
        }

        $this->ConnectParent("{B3B1D424-87A5-4F26-93C3-E49BF48873F9}");
    }

    public function ReceiveData($JSONString) {
        $data = json_decode($JSONString);
        if($this->ReadPropertyString("sensortype") == "THB") {
            $sensortype = $this->ReadPropertyString("sensortype");
            $sensorid = (string) "thb" .$this->ReadPropertyInteger("sensorid");
            SetValue($this->GetIDForIdent("ID"),$data->Buffer->$sensortype->$sensorid->id);
            SetValue($this->GetIDForIdent("TEMPERATURE"),$data->Buffer->$sensortype->$sensorid->temp);
            SetValue($this->GetIDForIdent("HUMIDITY"),$data->Buffer->$sensortype->$sensorid->hum);
            SetValue($this->GetIDForIdent("DEWPOINT"),$data->Buffer->$sensortype->$sensorid->dew);
            SetValue($this->GetIDForIdent("PRESS"),$data->Buffer->$sensortype->$sensorid->press);
            SetValue($this->GetIDForIdent("SEAPRESS"),$data->Buffer->$sensortype->$sensorid->seapress);
        }
        elseif($this->ReadPropertyString("sensortype") == "RAIN") {
            $sensortype = $this->ReadPropertyString("sensortype");
            $sensorid = (string) "rain" .$this->ReadPropertyInteger("sensorid");
            SetValue($this->GetIDForIdent("ID"),$data->Buffer->$sensortype->$sensorid->id);
            SetValue($this->GetIDForIdent("RAIN"),$data->Buffer->$sensortype->$sensorid->rate);
            SetValue($this->GetIDForIdent("TOTAL"),$data->Buffer->$sensortype->$sensorid->total);
            SetValue($this->GetIDForIdent("DELTA"),$data->Buffer->$sensortype->$sensorid->delta);
        }
        elseif($this->ReadPropertyString("sensortype") == "UV") {
            $sensortype = $this->ReadPropertyString("sensortype");
            $sensorid = (string) "uv" .$this->ReadPropertyInteger("sensorid");
            SetValue($this->GetIDForIdent("ID"),$data->Buffer->$sensortype->$sensorid->id);
            SetValue($this->GetIDForIdent("INDEX"),$data->Buffer->$sensortype->$sensorid->index);
        }
        elseif($this->ReadPropertyString("sensortype") == "TH") {
            $sensortype = $this->ReadPropertyString("sensortype");
            $sensorid = (string) "th" .$this->ReadPropertyInteger("sensorid");
            SetValue($this->GetIDForIdent("ID"),$data->Buffer->$sensortype->$sensorid->id);
            SetValue($this->GetIDForIdent("TEMPERATURE"),$data->Buffer->$sensortype->$sensorid->temp);
            SetValue($this->GetIDForIdent("HUMIDITY"),$data->Buffer->$sensortype->$sensorid->hum);
            SetValue($this->GetIDForIdent("DEWPOINT"),$data->Buffer->$sensortype->$sensorid->dew);
        }
        elseif($this->ReadPropertyString("sensortype") == "WIND") {
            $sensortype = $this->ReadPropertyString("sensortype");
            $sensorid = (string) "wind" .$this->ReadPropertyInteger("sensorid");
            SetValue($this->GetIDForIdent("ID"),$data->Buffer->$sensortype->$sensorid->id);
            SetValue($this->GetIDForIdent("DIRECTION"),$data->Buffer->$sensortype->$sensorid->dir);
            SetValue($this->GetIDForIdent("GUST"),$data->Buffer->$sensortype->$sensorid->gust);
            SetValue($this->GetIDForIdent("WIND"),$data->Buffer->$sensortype->$sensorid->wind);
            SetValue($this->GetIDForIdent("CHILL"),$data->Buffer->$sensortype->$sensorid->chill);
        }
    }
}