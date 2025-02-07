<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villager Discount</title>
    <style>
        .command{
            font-weight: 600;
         }
        .codeDiv{
            background-color: lightgray;
            padding: 2px 8px;
        }
        .tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
    </style>
</head>
<body>
    <input type="text" placeholder="Player's nickname..." id="name" onkeyup="getCommand()"> <span id="showUuid"></span><br>
    <input type="radio" name="line" onchange="getCommand()" checked> Premium<br>
    <input type="radio" name="line" id="offline" onchange="getCommand()"> Non-premium<br>
    <input type="checkbox" id="uuid" onchange="placeholderChange()"> Enter UUID instead of player's nickname<br>
    Curing level: <input type="number" min="1" max="5" id="cures" value="1" onchange="getCommand()"><br>
    
    <p id="result"></p>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script>
        function copyCommand() {
            var copyText = document.getElementsByClassName("codeDiv")[0].querySelector("code").innerHTML;
            navigator.clipboard.writeText(copyText);
            document.getElementById("myTooltip").innerHTML = "Command coppied!"
        }

        function placeholderChange(){
            if(document.getElementById("uuid").checked){
                document.getElementById("name").setAttribute("placeholder","Player's UUID...");
            }else{
                document.getElementById("name").setAttribute("placeholder","Player's nickname...");
            }
            getCommand()
        }

        function getCommand(){
            var playerData = {}
            if(document.getElementById("name").value!=null){
                if(document.getElementById("uuid").checked){
                    playerData.uuid=document.getElementById("name").value
                }else{
                    playerData.nickname=document.getElementById("name").value
                }
            }
            if(document.getElementById("offline").checked){
                playerData.offline = true;
            }

            $.ajax({
                url: "../mcUUID_to4int.php",
                type: "GET",
                data: playerData,
                success: function (data) {
                    let result = $.parseJSON(data)
                    
                    if(result.error){
                        document.getElementById("result").innerHTML = `<h3>${result.error}</h3>`
                    }else{
                        if(!playerData.uuid){
                            document.getElementById('showUuid').innerHTML=`(UUID: ${result.uuid})`
                        }
                        document.getElementById("result").innerHTML = `Copy that command to modify the closest Villager:<div class='codeDiv'><code class='command'>/data modify entity @e[type=minecraft:villager,sort=nearest,limit=1] Gossips set value [{Target: [I;${result.fourInt}], Type: "major_positive", Value: ${20*parseInt(document.getElementById("cures").value)}}]</code></div><div class="tooltip"><button onclick="copyCommand()"> <span class="tooltiptext" id="myTooltip">Copy to clipboard</span>Copy command</button></div>`
                    }
                }
            })
        }
    </script>
</body>
</html>