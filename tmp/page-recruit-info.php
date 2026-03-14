<?php
$recruitSalon = (is_page('recruit/info-guiches')) ? 'guiches' : 'soho' ;


?>
<article class="">
<div class="c-headline__outer">
    <div class="l-row--container c-gutter__row justify-start">
      <div class="c-headline__frame" >
        <h1 class="c-headline__title c-headline__title--long"><span class="">RECRUIT</span></h1>
        <div class="c-headline__detail">
    <p class="">
    <span class="c-ttl__xsmall text-clr3"><span class="text-clr1">#</span> <?php echo $recruitSalon ?> 募集要項</span></p>
        </div>
      </div>
    </div>
  </div>

  <section class=" mt-7 md:mt-8">
    <div class="l-row--container c-gutter__row justify-start">
      <div class="c-col--12 c-col__sm--12 c-col__xl--10">
      <dl class="p-recruit-info__dl">

<?php
  get_template_part('tmp/content/recruit', 'info-'.$recruitSalon );
?>


          </dl>
      </div>
    </div>


  </section>

</article>

