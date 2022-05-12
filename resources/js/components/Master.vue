<template>
    <div>
        <navbar/>
        <div class="app-main">
            <router-view/>
        </div>
    </div>
</template>

<script>
import Navbar from "./Navbar";

export default {
    name: 'Master',

    components: {
        Navbar
    }
}
</script>
