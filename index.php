<?php
    session_start();


    if(!isset($_GET['artifact']) | @$_GET['artifact']==''  && !isset($_GET['teamviewer']) | @$_GET['teamviewer']=='')
    {
        $_SESSION['flask_message']= "Por favor informe o numero do artefato  ou  teamviewer ID para localizar o artefato " ; 
    }
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Nuc Hardware Usage</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }
    .body .canva
    {
       font-family: arial;
    }
    .card
    {
        width:88% !important;
        border: 1px solid #333652 !important;
    }
 
   
    /* Safari */
    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .collapsible {
      background-color: #777;
      color: white;
      cursor: pointer;
      padding: 18px;
      width: 88%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 15px;
    }

    .active, .collapsible:hover {
      background-color: #555;
    }

    .content {
      padding: 0 18px;
      display: none;
      overflow: hidden;
      background-color: #f1f1f1;
      width: 88%;
    }

  </style>   
</head>
<body>

 

    <div class="container-fluid">
      <?php  
            if(isset($_SESSION['flask_message']) && $_SESSION['flask_message']!='')
            {
                echo '<p style="font-size:19 px ; color:red ;   line-break: strict;">'.$_SESSION['flask_message'].'</p>';
                unset($_SESSION['flask_message']);
                exit;
            }
      ?>
      <div class="row">
      

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Nuc Hardware</h1>
            <div class="btn-toolbar mb-2 mb-md-0">             
            
            </div>
          </div>
          <div class="loader" id='loader' style='display:block; margin-left: 41%; margin-top: 5%;margin-bottom:2%'></div>
          <div class="card" >
            <canvas class="my-4" id="myChart" width="900" height="380" ></canvas>
          </div>
          <br>
          <div class="card">
            <canvas class="my-4" id="myChart2" width="900" height="380" ></canvas>  
          </div>
          <br>
          <div class="card">
            <canvas class="my-4" id="myChart3" width="900" height="380" ></canvas>  
          </div>


          <h2>Nuc Temperature Report</h2>
          <button type="button" class="collapsible"> Nuc Temperature Report</button>
              <div class="content" >
                  <div class="table-responsive"  style='width:88% !important;' id='temptable'>
                   </div>
              </div>
         
            <br>
          <h2>Nuc Hardware</h2>

          <button type="button" class="collapsible" >Nuc Hardware</button>
              <div class="content" >
                  <p id='hwinfo'></p>
              </div>


         
        
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>





<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

window.onload=function()
{
  
   document.getElementById('loader').style.display="block";
   document.getElementById('myChart').style.display="none";
   document.getElementById('myChart2').style.display="none";
   document.getElementById('myChart3').style.display="none";
   cards = document.getElementsByClassName('card');
   for(var i= 0; i <(cards.length); i++ )
   {
      cards[i].style.display="none";
   }
   get();
}

function get()
{
    let artifact = "<?php echo @$_GET['artifact']; ?>";
    let teamviewer = "<?php echo @$_GET['teamviewer']; ?>";
    let period = "<?php echo @$_GET['period']; ?>";
    let csrf =  "<?php echo md5(time()); ?>";
    let form = new FormData();
    form.append('csrf',csrf);

    if(artifact!=null & artifact!='')
    {
        form.append('artifact',artifact);
    }
    if(teamviewer!=null & teamviewer!='')
    {
        form.append('teamviewer',teamviewer);
    }
    if(period!=null & period!='')
    {
        form.append('period',parseInt(period));
    }
    else form.append('period',30);

    getData(form);
}

async function getData(formdata)
{
    console.log(formdata);   
    const res = await fetch('getdata.php',
      {
        method: 'POST',
        body: formdata,
      },
    );
    const resData = await res.text();
    if(resData)
    {
        const ret = JSON.parse(resData) 
        if(ret)
        {
            if(ret.Status=='202' | ret.Status==202)
            {
                  console.log('ok');
                  for(var i= 0; i <(cards.length); i++ )
                  {
                      cards[i].style.display="block";
                  }
                  document.getElementById('loader').style.display='none';
                  document.getElementById('myChart').style.display="block";
                  document.getElementById('myChart2').style.display="block";
                  document.getElementById('myChart3').style.display="block";
                  showGraph(ret.dates, ret.freeMemory, ret.useMemory, ret.loadCPU, ret.hdd, ret.maxMemory, ret.freeCPU);
                  memoGraph(ret.dates, ret.useMemory, ret.freeMemory, ret.maxMemory);
                  cpuGraph(ret.dates, ret.freeCPU,ret.loadCPU);
                  temperatureReport(ret.temp)
                  console.log((ret.hwinfo));
                  document.getElementById('hwinfo').innerHTML =htmlEntities(ret.hwinfo);
            }
            else
            {
              Swal.fire({
                  title: ret.msg,
                  text: " ",
                  icon: "error"
                });
            }
        }
    }
}


function showGraph(datar , freeMemory, usesMemory, cpuUsage, hdd, maxmemory, freeCPU)
{
    //console.log(datar);
    //console.log(freeMemory);
    //console.log(usesMemory);
    //console.log(cpuUsage);
    //console.log(hdd);
    new Chart("myChart", {
        type: "line",
        data: {
          labels: datar,
          datasets: [{ 
              data:freeMemory,
              borderColor: "blue",
              fill:false,
              label:'Memoria Livre (MB)',
              tension: 0.2
          }, { 
              data:usesMemory,
              borderColor: "red",
              fill:false,
              label:'Memoria usada (MB)',
              tension: 0.2
          }, { 
              data:cpuUsage,
              borderColor: "green",
              fill:false,
              label:'CPU usada (%)',
              tension: 0.2
          },
          { 
            data:hdd,
            borderColor: "black",
            fill:false,
            label:'uso HD (%)',
            tension: 0.2
          },
          { 
            data:freeCPU,
            borderColor: "Cyan",
            fill:false,
            label:'CPU livre (%)',
            tension: 0.2
          }]
        },
        options: {
          legend: {display: true}
        }
      });
}

function memoGraph(datar, usesMemory, freeMemory, maxmemory)
{
    usesMemory.push(parseInt(maxmemory));
    var barColors = ["red", "green","blue","orange","brown"];
    var xValues = datar;
    var yValues = usesMemory
    new Chart("myChart2", {
        type: "bar",
        data: {
          labels: xValues,
          datasets: [{
            backgroundColor: barColors,
            label:'Memoria usada (MB)',
            data: yValues
          }]
        },
        options: {
          responsive: true,
          legend: {display: true},
          title: {
            display: true,
            text: "Uso de Memoria RAM  x Data"
          }
        }
    });
}

function cpuGraph(datar, freeCPU,cpuUsage)
{
    //cpuUsage.push(freeCPU);
    var barColors = ["#603F8B", "green","#FD49A0","orange","#90ADC6"];
    var xValues = datar;
    var yValues = freeCPU;
    new Chart("myChart3", {
        type: "bar",
        data: {
          labels: xValues,
          datasets: [{
            backgroundColor: barColors,
            label: 'CPU Iddle (%)',
            data: yValues,
            tension: 0.2
          },
          {
            backgroundColor: ["#603F8B", "blue","#05445E","#3D550C","#90ADC6"],
            label: 'CPU Uso (%)',
            data: cpuUsage,
            tension: 0.2
          }]
        },
        options: {
          responsive: true,
          legend: {display: true},
          title: {
            display: true,
            text: "CPU Iddle x Data"
          }
        }
    });
}

function htmlEntities(str) {
    return String(str).replace('/n', '').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function temperatureReport(temps)
{

    const div = document.getElementById('temptable');
    let html = `<table class="table table-striped table-sm">
                <thead>
                    <tr>
                      <th>Report de temperatura do nuc</th>
                    </tr>
                </thead>`

    html += `<tbody>`;
    for(i =0 ; i< temps.length; i++)
    {
        html+='<tr><td>'+htmlEntities(temps[i])+'</td></tr>';
    }

    html += `</tbody></table>`;

    // adding HTML to your div
    div.innerHTML = html;
}



var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>