<?php
$recruitSalon = (is_page('recruit/info-guiches')) ? 'guiches' : 'soho' ;


?>
<article class="">
<div class="headline__outer">
    <div class="container-width mx-auto flex flex-wrap justify-start px-gutter-row xl:px-0">
      <div class="headline__frame" >
        <h1 class="headline__title headline__title--long"><span class="">RECRUIT</span></h1>
        <div class="headline__detail">
    <p class="">
    <span class="ttl__xsmall text-clr3"><span class="text-clr1">#</span> <?php echo $recruitSalon ?> 募集要項</span></p>
        </div>
      </div>
    </div>
  </div>

  <section class=" mt-7 md:mt-8">
    <div class="container-width mx-auto flex flex-wrap justify-start px-gutter-row xl:px-0">
      <div class="w-full sm:w-full xl:w-10/12">
      <dl class="recruit-info__dl">

<?php
  get_template_part('tmp/content/recruit', 'info-'.$recruitSalon );
?>


          </dl>
      </div>
    </div>


  </section>

</article>

