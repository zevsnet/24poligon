<template functional>
    <div class="property-input">
        <div><label :for="'ORDER_PROP_' + props.property.ID">{{props.property.NAME}}<span v-if="props.property.REQUIRED === 'Y' && props.property.CODE !== 'ZIP'" style="color: red;">*</span>:</label></div>
        <div>
            <slot :property="props.property"></slot>
        </div>
    </div>
</template>

<script>
    /* компонент для отрисовки сгруппированных названия и поля */
    export default {
        name: 'form-group',
        props: {
            property: {
                type: Object,
                required: true
            }
        },
        created() {

        }
    }
</script>