<template>
  <div class="formInput">
    <v-date-picker v-model="date" color="green" @input="onDateRangeChange" class="dateWrp">
      <template v-slot="{ togglePopover }">
        <input
          :name="propsData.name"
          :class="{ error: inputData.$invalid && showError }"
          :placeholder="propsData.placeholder"
          :value="dateVal"
          @click.prevent="togglePopover()"
        />
        <button @click.prevent="togglePopover()">
          <span class="icon icon-calendar"></span>
        </button>
        <!-- <button class="icon icon-calendar" @click="togglePopover()"></button> -->
      </template>
    </v-date-picker>
    <!-- <v-date-picker class="inline-block h-full" v-model="date"> -->
    <!-- <template v-slot="{ inputValue, togglePopover }">
      <div class="flex items-center">
        <button
          class="p-2 bg-blue-100 border border-blue-200 hover:bg-blue-200 text-blue-600 rounded-l focus:bg-blue-500 focus:text-white focus:border-blue-500 focus:outline-none"
          @click="togglePopover()"
        >
        asd
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            class="w-4 h-4 fill-current"
          >
            <path
              d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"
            ></path>
          </svg>
        </button>
        <input
          :value="inputValue"
          class="bg-white text-gray-700 w-full py-1 px-2 appearance-none border rounded-r focus:outline-none focus:border-blue-500"
          readonly
        />
      </div>
    </template> -->
    <!-- </v-date-picker> -->
    <span v-if="inputData.$invalid && showError" class="error">
      <span v-if="!inputData.required">{{ messages.required }}</span>
      <span v-if="!inputData.email">{{ messages.email }}</span>
      <span v-if="!inputData.minLength">{{ messages.min }}</span>
      <span v-if="!inputData.maxLength">{{ messages.max }}</span>
    </span>
  </div>
</template>

<script>
export default {
  props: {
    messages: {
      type: Object,
      default: () => {},
    },
    inputData: {
      type: Object,
      default: () => {},
    },
    showError: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      date: new Date(),
      val: this.inputValue,
      changed: false,
    }
  },
  computed: {
    dateVal() {
      const options = {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        timezone: 'UTC',
      }
      if (this.changed) {
        return this.date.toLocaleString('ru', options)
      } else {
        return ''
      }
    },
  },
  methods: {
    onDateRangeChange() {
      this.changed = true
      this.$emit('input', this.dateVal)
    },
  },
}
</script>

<style lang="scss" scoped>
span {
  width: 100%;
}
input {
  width: 100%;

  background-color: unset;
  padding: 12px 13px;

  display: inline-block;
  width: inherit;
  box-sizing: border-box;
  font-weight: 500;
  font-size: 16px;
  line-height: 114%;
  color: #9ba2ac;
}
.formInput {
  width: 100%;
}
.dateWrp {
  display: flex;
  justify-content: space-between;
  border-bottom: 1px solid rgba(53, 53, 53, 0.14);
  margin-bottom: 15px;
  .icon {
    // position: absolute;
    display: block;
    width: auto;
    pointer-events: none;
    color: #9ba2ac;
  }
}
.error {
  border-color: red;
  color: red;
}
</style>
