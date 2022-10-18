<?php
    session_start();
    // Flash message helper
    function flash($name='',$type='',$message='',$class=''){
        if(!empty($name)){
            //No message, create it
            if(!empty($message) && empty($_SESSION[$name])){
              if(!empty( $_SESSION[$name])){
                  unset( $_SESSION[$name]);
              }
              if(!empty( $_SESSION[$name.'_class'])){
                  unset( $_SESSION[$name.'_class']);
              }
              $_SESSION[$name] = $message;
              $_SESSION[$name.'_class'] = $class;
            }
            //Message exists, display it
            elseif(!empty($_SESSION[$name]) && empty($message) && $type === 'alert'){
              $class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : 'success';
              echo '<div class="'.$class.'" id="msg-flash" role="alert">'.$_SESSION[$name].'
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
              unset($_SESSION[$name]);
              unset($_SESSION[$name.'_class']);
            }elseif(!empty($_SESSION[$name]) && empty($message) && $type === 'toast'){
              $class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : 'success';
              echo '<div class="toast-container position-fixed bottom-0 end-0 z-index-toast p-3">
                      <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="'.$class.'">
                          <img src="'.URLROOT.'/img/other/info_24px.png" class="rounded me-2" alt="Action Icon">
                          <strong class="me-auto text-white">'.SITENAME.'</strong>
                          <small class="text-white">Now</small>
                          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                        '.$_SESSION[$name].'
                        </div>
                      </div>
                    </div>
                    <script>
                      const toastLiveExample = document.getElementById("liveToast")
                      const toast = new bootstrap.Toast(toastLiveExample)
                      toast.show()
                    </script>';
              unset($_SESSION[$name]);
              unset($_SESSION[$name.'_class']);
            }
          }
    }