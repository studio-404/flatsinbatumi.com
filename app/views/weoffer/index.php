<?php 
require_once("app/functions/l.php"); 
require_once("app/functions/string.php"); 
require_once("app/functions/strip_output.php"); 
$l = new functions\l(); 
$string = new functions\string(); 
echo $data['headerModule']; 
echo $data['headertop']; 
?>

<main>
    <?php $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>
    <a href="http://www.facebook.com/sharer.php?s=100&p[url]=<?=$actual_link?>" target="_blank" class="facebook-share"><i class="fa fa-facebook-square fa-2x"></i></a>
    <section class="container">
      <section class="row containerRow">
        
        <?=$data["leftnav"]?>

        <section class="col-md-8 right ">
            <section class="list-group">
              <section class="list-group-item" style="color: white">
                <a href="/" class="close router-link-active" style="color: rgb(26, 185, 73); opacity: 1; text-shadow: none; position: relative; z-index: 999;"><i class="fa fa-close" style="font-size: 28px;"></i></a>
                <section class="row anketadata">
                  <section class="col-md-12"></section>
                  
                  <section class="col-md-12">
                    
                    <section class="col-md-12">
                      <section class="row">
                        <section class="col-xs-12">
                          <h4 class="text-center text-uppercase">Прошу вас заполнить анкету</h4>
                          <p class="text-center text-uppercase">
                            и я вам предложу лучшие варианты в течении 
                            <strong style="color: rgb(20, 202, 33);">45 мин</strong> 
                            <br />
                          </p>
                        </section>
                      </section>
                    </section>
                    <div style="clear:both"></div>

                    <section class="output-message" style="background-color: rgba(20,202,33,.75);">&nbsp;</section>

                    <section class="form-group">
                      <label style="width:100%">Дата заезда*</label>
                      <input type="text" readonly="readonly" name="checkindate" id="checkindate" class="form-control date" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                    </section>

                    <section class="form-group">
                      <label style="width:100%">Дата отъезда*</label>
                      <input type="text" readonly="readonly" name="checkoutdate" id="checkoutdate" class="form-control date" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                    </section>

                    <script type="text/javascript">
                    $(".date").datepicker({ 
                      format: 'dd/mm/yyyy', 
                      autoclose: true 
                    }); 
                    </script> 

                    <section class="form-group">
                      <section class="row">
                        <section class="col-md-6 filter-select-item">
                          <label>Колличество человек</label> 
                          <section class="row">
                            <section class="col-xs-6">
                              <label>Взрослые</label> 
                              <input type="number" step="1" name="adults" id="adults" class="form-control" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                            </section> 
                            <section class="col-xs-6">
                              <label>Дети</label> 
                              <input type="number" step="1" name="children" id="children" class="form-control" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                            </section>
                          </section>
                        </section> 
                        <section class="col-md-6 filter-select-item">
                          <label>Какую максимальную сумму</label> 
                          <section class="row">
                            <section class="col-md-12">
                              <label>вы можите платить в сутки? ($)</label> 
                              <input type="number" step="5" name="canpay" id="canpay" placeholder="Например: 50$" class="form-control" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                            </section>
                          </section>
                        </section>
                      </section>
                    </section>

                    <section class="form-group">
                      <section class="row">
                        <section class="col-md-12">
                          <label> Ваши пожелания </label>
                          <textarea cols="30" rows="2" name="willings" id="willings" placeholder="Например: хочу, чтобы у квартиры был вид на море, в крвартире должен быть евроремонт. Хочу, чтобы в указанное время нас встретили в аэропорту/жд. вокзале" class="form-control offers-textarea" style="border: 1px solid hsla(0,0%,100%,.43)!important;"></textarea>
                        </section>
                      </section>
                    </section>

                    <section class="form-group">
                      <section class="row">
                        <section class="col-md-4">
                          <label>Имя*</label> 
                          <input type="text" step="5" name="firstname" id="firstname" class="form-control" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                        </section> 
                        <section class="col-md-4">
                          <label>
                            Телефон
                            <span style="color: rgb(255, 90, 91);">* (обязательно)</span>
                          </label> 
                          <input type="text" step="5" name="phone" id="phone" placeholder="с кодом" class="form-control" style="border: 1px solid hsla(0,0%,100%,.43)!important;"/>
                        </section> 
                        <section class="col-md-4">
                          <label>
                            Email 
                            <span style="color: rgb(255, 90, 91);">* (обязательно)</span>
                          </label> 
                          <input type="text" step="5" name="email" id="email" placeholder="Укажите правильно" class="form-control" style="border: 1px solid hsla(0,0%,100%,.43)!important;" />
                        </section>
                      </section>
                    </section>

                    <section class="form-group">
                      <section class="row">
                        <br> 
                        <section class="col-md-12">
                          <span style="">Телефон или email должны быть заполнены</span> 
                          <br> <br>
                        </section>
                      </section>
                    </section>

                    <section class="form-group">
                      <section class="row">
                        <section class="col-md-12">
                          <button class="btn btn-success pull-right btn-block btn-lg" id="sendButton" disabled="disabled" style="font-size: 18px;">Отправить</button>
                        </section>
                      </section>
                    </section>
                    

                  </section>
                </section>
              </section>
            </section> 




          </section>
      </section>
    </section>

    <a href="#" class="usefullinformation" data-show="false">Полезная информация</a>
  </main>

  <script type="text/javascript">
  $(document).on("keyup", "#phone", function(){
    if($(this).val()!=""){
      $("#sendButton").attr("disabled", false);
    }else if($(this).val()=="" && $("#email").val()==""){
      $("#sendButton").attr("disabled", true);
    }
  });

  $(document).on("keyup", "#email", function(){
    if($(this).val()!=""){
      $("#sendButton").attr("disabled", false);
    }else if($(this).val()=="" && $("#phone").val()==""){
      $("#sendButton").attr("disabled", true);
    }
  });
  </script>

<?=$data['footer']?>