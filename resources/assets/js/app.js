
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue';
import VueHtmlToPaper from 'vue-html-to-paper';

// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';
import vueMultiSelect from 'vue-multi-select';
import 'vue-multi-select/dist/lib/vue-multi-select.css';
Vue.use(Loading);
Vue.use(vueMultiSelect);
import VuejsDialog from 'vuejs-dialog';
 
// include the default style
import 'vuejs-dialog/dist/vuejs-dialog.min.css';
 
// Tell Vue to install the plugin.
Vue.use(VuejsDialog, {
  html: true,
  loader: true,
  okText: 'Proceed',
  cancelText: 'Cancel',
  animation: 'bounce'
});

const options = {
    name: '_blank',
    specs: [
      'fullscreen=yes',
      'titlebar=yes',
      'scrollbars=yes'
    ],
    styles: [
      'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
      'https://unpkg.com/kidlat-css/css/kidlat.css',
      `.print-buttons{
        display:none;
      }
      
      `
    ]
  }

  Vue.use(VueHtmlToPaper, options);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('home-page', require('./components/Home/Homepage.vue'));

Vue.component('student-index', require('./components/Student/Student.vue'));
Vue.component('student-create', require('./components/Student/StudentCreate.vue'));

Vue.component('staff-index', require('./components/Staff/Staff.vue'));
Vue.component('staff-create', require('./components/Staff/StaffCreate.vue'));

Vue.component('staff-classadvisor', require('./components/Staff/ClassAdvisor.vue'));


Vue.component('schedule-index', require('./components/Schedule/Schedule.vue'));
Vue.component('schedule-assign', require('./components/Schedule/ScheduleAssign.vue'));
Vue.component('schedule-view', require('./components/Schedule/ScheduleView.vue'));


Vue.component('student-attendance-create', require('./components/StudentAttendance/StudentAttendanceCreate.vue'));
Vue.component('student-attendance-create-special', require('./components/StudentAttendance/StudentAttendanceCreateSpecial.vue'));
Vue.component('student-attendance-remove-special', require('./components/StudentAttendance/StudentAttendanceRemoveSpecial.vue'));


Vue.component('student-attendance-edit', require('./components/StudentAttendance/StudentAttendanceEdit.vue'));
Vue.component('student-attendance-viewbyday', require('./components/StudentAttendance/StudentAttendanceViewByDay.vue'));
Vue.component('student-attendance-viewbymonth', require('./components/StudentAttendance/StudentAttendanceViewByMonth.vue'));
Vue.component('student-attendance-viewbymonth-count', require('./components/StudentAttendance/StudentAttendanceViewByMonthCount.vue'));
Vue.component('student-attendance-viewbyoverall', require('./components/StudentAttendance/StudentAttendanceViewByOverall.vue'));
Vue.component('student-attendance-viewsubject', require('./components/StudentAttendance/StudentAttendanceViewBySubject.vue'));
Vue.component('student-attendance-viewbyprincipal', require('./components/StudentAttendance/StudentAttendanceViewByPrincipal.vue'));
Vue.component('student-attendance-edit-permissions', require('./components/StudentAttendance/StudentAttendanceEditPermissions.vue'));

Vue.component('student-dayat-class-repeat', require('./components/StudentAttendance/StudentDayAttendanceClassRepeat.vue'));



Vue.component('staff-attendance-create', require('./components/StaffAttendance/StaffAttendanceCreate.vue'));
Vue.component('staff-attendance-edit', require('./components/StaffAttendance/StaffAttendanceEdit.vue'));
Vue.component('staff-attendance-viewbyday', require('./components/StaffAttendance/StaffAttendanceViewByDay.vue'));
Vue.component('staff-attendance-viewbymonth', require('./components/StaffAttendance/StaffAttendanceViewByMonth.vue'));
Vue.component('staff-attendance-viewbyoverall', require('./components/StaffAttendance/StaffAttendanceViewByOverall.vue'));


Vue.component('staff-approvals-index', require('./components/Staffapprovals/StaffApprovalsIndex.vue'));
Vue.component('staff-myprofile', require('./components/Staffapprovals/StaffMyProfile.vue'));
Vue.component('staff-approvals-show', require('./components/Staffapprovals/StaffMyApprovals.vue'));

Vue.component('academic-page', require('./components/Academic/Index.vue'));

Vue.component('staff-myschedule', require('./components/Schedule/StaffMySchedule.vue'));

Vue.component('tabs', {
  template: `
      <div>
      <hr>
          <div class="tabs">
            <ul class="tab-head-list" style="padding-left:0;">
              <li v-for="tab in tabs" :class="{ 'is-active': tab.isActive }" style="display:inline;padding:15px;">
                  <a :href="tab.href" @click="selectTab(tab)" :style="selectTabColor(tab)">{{ tab.name }}</a>
              </li>
            </ul>
          </div>
          <hr>
          <div class="tabs-details">
              <slot></slot>
          </div>
      </div>
  `,
  
  data() {
      return {tabs: [] };
  },
  
  created() {
      
      this.tabs = this.$children;
      
  },
  methods: {
      selectTab(selectedTab) {
          this.tabs.forEach(tab => {
              tab.isActive = (tab.name == selectedTab.name);
          });
      },
      selectTabColor(tab){
        this.tabs.forEach(tab => {
          if(tab.isActive){
            return `style="color:red;"`;
          }else{
            return `style="color:blue;"`;
          }
        });
      }

  }
});

Vue.component('tab', {
  
  template: `

      <div v-show="isActive"><slot></slot></div>

  `,
  
  props: {
      name: { required: true },
      selected: { default: false}
  },
  
  data() {
      
      return {
          isActive: false
      };
      
  },
  
  computed: {
      
      href() {
          return '#' + this.name.toLowerCase().replace(/ /g, '-');
      }
  },
  
  mounted() {
      
      this.isActive = this.selected;
      
  }
});

const app = new Vue({
    el: '#app'
});
