<script>
import { watch, ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

import { COVER_ART_PLACEHOLDER } from '@/Constants/urls';

import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

import { isValidUrl } from '../../../Utils/urlUtils';

export default {
  components: {
    TextInput,
    NumberInput,
    InputLabel
  },
  setup() {
    const newGameCoverArt = ref(null);

    const form = useForm({
      title: '',
      thumbnailUrl: '',
      playtime: 0,
      estimatedPlaytime: 0
    });

    const thumbnailUrl = computed({
      get: () => form.thumbnailUrl,
      set: (value) => form.thumbnailUrl = value,
    });

    watch(thumbnailUrl, async (newUrl, oldUrl) => {
      if (newUrl) {
        if (await isValidUrl(newUrl)) {
          newGameCoverArt.value.src = newUrl;
        } else {
          newGameCoverArt.value.src = COVER_ART_PLACEHOLDER;
        }
      }
    }, { immediate: true });

    return {
      form,
      thumbnailUrl,
      newGameCoverArt
    };
  }
}
</script>
<template>
    <form>
      <InputLabel for="title" value="Title" />
      <TextInput
        id="title"
        class="mt-1 block w-full sm:w-3/4"
        required
        placeholder="Enter game title..."
        v-model="form.title"
      />
      <InputLabel for="coverArt" value="Cover Art URL" class="mt-5"/>
      <TextInput
        id="coverArt"
        class="mt-1 block  w-full sm:w-3/4"
        required
        placeholder="Enter cover art URL"
        v-model="form.thumbnailUrl"
      />
      <img v-if="thumbnailUrl" ref="newGameCoverArt" class="shadow-md shadow-gray-400 object-center object-cover h-28 w-24 rounded-lg ml-5 mt-5 sm:h-36 sm:w-24" />
    <InputLabel for="playtime" value="Playtime (hrs)" class="mt-5"/>
    <NumberInput
        id="playtime"
        class="mt-1 block w-full sm:w-2/5"
        required
        placeholder=""
        v-model="form.playtime"
    />

    <InputLabel for="estimatedPlaytime" value="Estimated Playtime to Complete (hrs)" class="mt-5"/>
    <NumberInput
        id="estimatedPlaytime"
        class="mt-1 block w-full sm:w-2/5"
        required
        placeholder=""
        v-model="form.estimatedPlaytime"
    />
    </form>
  </template>
