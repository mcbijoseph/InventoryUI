function WebRequest(method, url, data, contentType, func, isRaw = false)
{
    
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        
           // Typical action to be performed when the document is ready:
           if(isRaw)
            func(xhttp.responseText);
           else
            func( ParseStringToObject(contentType, xhttp.responseText));
            
        }
    };
    xhttp.open(method, url, true);
    xhttp.setRequestHeader('Content-Type', contentType);
    
    //FORMATTING REQUEST
    var request = ParseObjectToString(contentType, data);
    
    //console.log(request);
    
    xhttp.send(request);
}

////////////PARSERS//////////////////
function ParseStringToObject(contentType, data)
{
    contentType = contentType.toLowerCase();
    
    if(contentType == "application/json")
        return JSON.parse(data);
        
    if(contentType == "application/xml" || contentType == "text/xml")
    {
        return   cleanXML2JSON(data);
    }
    return data;
}

function ParseObjectToString(contentType, data)
{
    if(contentType == "application/json")
    {
        if(data.constructor === " ".constructor)
        {
            return data;
        }
        else if(canJSON(data))
        {
            return JSON.stringify(data);
        }
        return data;
    }
    else if( canJSON(data))
    {
        var Obj = null;
        
        if(data.constructor === " ".constructor)
        {
            Obj = JSON.parse(data);
        }
        return OBJtoXML(Obj);
    }
    return null;
}

function OBJtoXML(obj) {
  var xml = '';
  for (var prop in obj) {
    xml += obj[prop] instanceof Array ? '' : "<" + prop + ">";
    if (obj[prop] instanceof Array) {
      for (var array in obj[prop]) {
        xml += "<" + prop + ">";
        xml += OBJtoXML(new Object(obj[prop][array]));
        xml += "</" + prop + ">";
      }
    } else if (typeof obj[prop] == "object") {
      xml += OBJtoXML(new Object(obj[prop]));
    } else {
      xml += obj[prop];
    }
    xml += obj[prop] instanceof Array ? '' : "</" + prop + ">";
  }
  var xml = xml.replace(/<\/?[0-9]{1,}>/g, '');
  return '<xml>'+xml+'</xml>'
}
function canJSON(value) {
    try {
        
        JSON.stringify(value);
        return true;
    } catch (ex) {
        return false;
    }
}
function parseXml(xml) {
   var dom = null;
   if (window.DOMParser) {
      try { 
         dom = (new DOMParser()).parseFromString(xml, "text/xml"); 
      } 
      catch (e) { dom = null; }
   }
   else if (window.ActiveXObject) {
      try {
         dom = new ActiveXObject('Microsoft.XMLDOM');
         dom.async = false;
         if (!dom.loadXML(xml)) // parse error ..

            window.alert(dom.parseError.reason + dom.parseError.srcText);
      } 
      catch (e) { dom = null; }
   }
   else
      alert("cannot parse xml string!");
   return dom;
}

function cleanXML2JSON(data)
{
  var cleanNode = [];
  var nodes =  xmlToJson( parseXml( data ))["nodes"]["node"];
  if(Array.isArray(nodes))
  {
    cleanNode = nodes;
  }
  else 
    cleanNode.push(nodes);
  //HasCleanup
  
  
  console.log(JSON.stringify(cleanNode));
  
  return cleanNode;
}



function xmlToJson(xml) {
	
	// Create the return object
	var obj = {};

	if (xml.nodeType == 1) { // element
		// do attributes
		if (xml.attributes.length > 0) {
		obj["@attributes"] = {};
			for (var j = 0; j < xml.attributes.length; j++) {
				var attribute = xml.attributes.item(j);
				obj["@attributes"][attribute.nodeName] =  attribute.nodeValue;
			}
		}
	} else if (xml.nodeType == 3) { // text
		obj = xml.nodeValue;
	}

	// do children
    
	if (xml.hasChildNodes()) {
		for(var i = 0; i < xml.childNodes.length; i++) {
			 var item = xml.childNodes.item(i);
			 var nodeName = item.nodeName;
            
             if(item.constructor.name == "Element")
             {
                try{
                
                if(item.childNodes.item(0).constructor.name == "Text")
                {
                    console.log(item.childNodes.item(0).constructor.name);
                    obj[nodeName] = item.childNodes.item(0).nodeValue;
                    continue;
                }
                }catch(e)
                {
                    console.log(e);
                }
             }
             
            if (typeof(obj[nodeName]) == "undefined") {
                	obj[nodeName] = xmlToJson(item);
			} else {
				if (typeof(obj[nodeName].push) == "undefined") {
					var old = obj[nodeName];
					obj[nodeName] = [];
					obj[nodeName].push(old);
				}
				obj[nodeName].push(xmlToJson(item));
			}
		}
	} 
       
	return obj;
}
