{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="المستخدمون" icon="la la-question" :link="backpack_url('user')" />
<x-backpack::menu-item title="المزادات" icon="la la-question" :link="backpack_url('auction')" />
<x-backpack::menu-item title="تفاصيل المزادات" icon="la la-question" :link="backpack_url('auctiondetail')" />
<x-backpack::menu-item title="المزايدات" icon="la la-question" :link="backpack_url('bid')" />
<x-backpack::menu-item title="التصنيفات" icon="la la-question" :link="backpack_url('category')" />
<x-backpack::menu-item title="الاشعارات" icon="la la-question" :link="backpack_url('notification')" />
