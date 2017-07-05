
<header class="header">
    <div class="ui teal inverted vertical masthead center aligned segment borderless">
      <div class="ui container">
        <div class="ui secondary inverted menu">
            <div class="left item">
                <a class="item" href="?p=home">HOME</a>
                <a class="item" href="?p=lowongan">CARI PEKERJAAN</a>
                <a class="item" href="#">BANTUAN</a>
            </div>
            <div class="right item">
                
                <?php
                if (isset($_SESSION['user_session'])){         
                ?>
                    <a class="ui large black inverted button" href="manage" style="margin-right: 1em">MANAGE</a>
                    <a class="ui large yellow inverted button" href="?p=logout">LOGOUT</a>
                <?php
                }
                else if(isset($_SESSION['admin_session'])){
                    ?>
                    <a class="ui large black inverted button" href="admin" style="margin-right: 1em">ADMIN</a>
                    <a class="ui large yellow inverted button" href="?p=logout">LOGOUT</a>
                <?php
                }
                else{
                ?>
                    <a class="ui inverted yellow basic button" href="?p=login">LOGIN</a>        
                <?php
                }
                ?>
                
            </div>
        </div>
      </div>

    </div> 
</header>   