<?php $this->setSeiteTitle("login")?>
<?php $this->start("head") ?> 
<link rel="stylesheet" href="<?php echo PROOT ?>/css/register/login.css">
<?php $this->end()?>
<?php $this->start("body") ?>  
<div class="container">
    <div class="card my-5">
        <div class="card-header">
            <h3>login</h3>
        </div>
        <div class="card-body">
            <form action="<?php echo PROOT ?>register/check" method="POST">
                <div class="form-group">
                    <label for="email">email</label>
                    <input type="email" name="email" id="email" class="form-control <?php if(isset($this->error)) echo "error-input-email" ?>" value="<?php if(isset($this->email)) echo $this->email ?>">
                    <?php if(isset($this->error)){?>
                    <small class="error-email"><?php echo $this->error ?></small>
                    <?php }?>
                </div>
                <div class="form-group">
                    <label for="password">password</label>
                    <input type="password" name="password" id="password" class="form-control <?php if(isset($this->passworderror)) echo "error-input-password" ?>" value="<?php if(isset($this->password)) echo $this->password ?>"> 
                    <?php if(isset($this->passworderror)){?>
                    <small class="error-email"><?php echo $this->passworderror ?></small>
                    <?php }?>
                </div>
                <div class="form-group">
                    <input type="password" name="password-confirm" id="" class="form-control">
                </div>
                <div class="form-check my-3">
                    <input class="form-check-input" type="checkbox" name="rememberMe" value="on" id="rememberme">
                    <label class="form-check-label" for="rememberme">
                        remember me
                    </label>
                </div>
                <input type="submit" value="login" class="btn btn-success btn-lg save-btn">
                <a href="" class="btn btn-success btn-lg save-btn mx-3">Register</a>
            </form>
        </div> 
    </div>
</div>
<?php $this->end()?> 