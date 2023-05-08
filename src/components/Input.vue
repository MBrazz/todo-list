<script setup>

const props = defineProps({
  modelValue: [String, Number]
})

</script>

<template>
  <input
          type="text"
          name="content"
          id="content"
          placeholder="Ex: lavar a louça"
          :value="modelValue"
          @input="$emit('update:modelValue', $event.target.value)"
        />
</template>