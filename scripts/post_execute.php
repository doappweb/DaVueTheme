<?php

echo '<div class="modal fade" id="modal-davue-welcome" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Thank you for installing the Davue theme!</h4>
            </div>
            <div class="modal-body">
                <h3>Requirements:</h3>
                <ul>
                    <li>- php ^7.3</li>
                    <li>- Apache 2.4</li>
                    <li>- enabled apache mod_rewrite</li>
                </ul>
                
                <h3>Recommendations:</h3>
                <ul>
                    <li>- set the DaVue theme as the "Default" theme (Admin > Themes > Default Theme)</li>
                    <li>- leave "System Administration User" in the standard SuiteP theme</li>
                </ul>
                
                <h3>Usage Rights</h3>
                
                DaVueTheme is licensed under the <a href="https://opensource.org/licenses/MIT">MIT license</a>.<br> 
                This allows you to do pretty much anything you want as long as you include the copyright in "all copies or substantial portions of the Software."<br> 
                Attribution is not required (though very much appreciated).
                <br><br>
                <strong>What you are allowed to do with DaVueTheme:</strong>
                <ul>
                    <li>- use in commercial projects</li>
                    <li>- use in personal/private projects</li>
                    <li>- modify and change the work</li>
                    <li>- distribute the code</li>
                    <li>- sublicense: incorporate the work into something that has a more restrictive license</li>
                </ul>
                <br>
                <strong>What you are not allowed to do with DaVueTheme:</strong>
                <ul>
                    <li> - the work is provided "as is". You may not hold the author liable</li>
                </ul>
            </div>
        </div>
    </div>
</div>';

echo "<script>$('#modal-davue-welcome').modal('show');</script>";
echo "<script>toggleDisplay('displayLog')</script>";

?>
