<template>
    <div class="product-page" v-if="product">
      <div class="image-gallery">
          <img :src="currentImage" alt="Product image" class="main-image">
          <div class="gallery-nav">
            <button class="nav-button prev" @click="prevImage"><div class="arrow"></div></button>
            <button class="nav-button next" @click="nextImage"><div class="arrow"></div></button>
          </div>
        <div class="thumbnail-container">
          <div v-for="(image, index) in product.images" :key="index" 
               class="thumbnail-wrapper"
               :class="{ 'active': currentImage === image }"
               @click="currentImage = image">
            <img :src="image" alt="Thumbnail" class="thumbnail">
          </div>
        </div>
      </div>
      <div class="company-name">SNEAKER COMPANY</div>
      <div class="product-info">
        <h1 class="product-title">{{ product.name }}</h1>
        <p class="product-description">{{ product.description }}</p>
        <div class="price-info">
            <div class="price-row">
                <span class="current-price">${{ product.price.discounted.toFixed(2) }}</span>
                <span class="discount">{{ product.discount.amount }}%</span>
            </div>
            <span class="original-price">${{ product.price.full.toFixed(2) }}</span>
        </div>
        <div class="add-to-cart-container">
          <div class="quantity-selector">
            <button @click="decreaseQuantity">-</button>
            <span>{{ quantity }}</span>
            <button @click="increaseQuantity">+</button>
          </div>
          <button class="add-to-cart-button" @click="addToCart">
            <font-awesome-icon :icon="['fas', 'shopping-cart']" /> 
            <span>Add to cart</span>
          </button>
        </div>
      </div>
    </div>
    <div v-else>Loading...</div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

export default {
    name: 'Product',
    setup() {
        const route = useRoute()
        const router = useRouter()
        const product = ref({
            name: '',
            description: '',
            price: { full: 0, discounted: 0 },
            discount: { amount: 0 },
            images: []
        })
        const currentImage = ref('')
        const quantity = ref(0)
        const error = ref(null)
        const currentImageIndex = ref(0)

        const prevImage = () => {
        currentImageIndex.value = (currentImageIndex.value - 1 + product.value.images.length) % product.value.images.length
            currentImage.value = product.value.images[currentImageIndex.value]
        }

        const nextImage = () => {
            currentImageIndex.value = (currentImageIndex.value + 1) % product.value.images.length
            currentImage.value = product.value.images[currentImageIndex.value]
        }
  
        const fetchProduct = async (slug) => {
            try {
                const response = await axios.get(`/client/products/${slug}`)
                // console.log('API response:', response.data)
                if (response.data && response.data.data) {
                    product.value = response.data.data
                    if (product.value.images && product.value.images.length > 0) {
                        currentImage.value = product.value.images[0]
                    }
                } else {
                    error.value = 'Invalid data structure received from API'
                }
            } catch (err) {
                router.push('/not-found')
                console.error('Error fetching product:', err)
            }
        }
  
        const increaseQuantity = () => {
            quantity.value++
        }
    
        const decreaseQuantity = () => {
            if (quantity.value > 0) quantity.value--
        }
    
        const addToCart = () => {
            console.log(`Added ${quantity.value} of ${product.value.name} to cart`)
        }
  
        onMounted(() => {
            const slug = route.params.slug
            fetchProduct(slug)
        })
  
        return {
            product,
            currentImage,
            quantity,
            error,
            increaseQuantity,
            decreaseQuantity,
            addToCart,
            prevImage,
            nextImage
        }
    }
}
</script>

<style scoped>
.product-page {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  max-width: 1440px;
  margin: 0 auto;
  padding: 10%;
  font-family: 'Kumbh Sans', sans-serif;
}

.image-gallery, .product-info {
  width: 46%;
}

/* .productr-info {
    align-items: center;
} */

.gallery-nav {
  display: none;
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  transform: translateY(-50%);
  justify-content: space-between;
  padding: 0 20px;
}

.nav-button {
  background-color: white;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.arrow {
  width: 10px;
  height: 10px;
  border-style: solid;
  border-color: #1D2026;
  border-width: 2px 2px 0 0;
  display: inline-block;
}

.prev .arrow {
  transform: rotate(-135deg);
}

.next .arrow {
  transform: rotate(45deg);
}

.main-image {
  width: 100%;
  border-radius: 20px;
  margin-bottom: 20px;
}

.company-name {
  display: none;
  color: hsl(26, 100%, 55%);
  font-weight: 700;
  font-size: 0.8rem;
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-top: 20px;
  margin-bottom: 10px;
}

.thumbnail-container {
  display: flex;
  justify-content: space-between;
}

.thumbnail-wrapper {
  width: 22%;
  border-radius: 10px;
  cursor: pointer;
  overflow: hidden;
  aspect-ratio: 1 / 1;
}

.thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  transition: opacity 0.3s;
}

.thumbnail-wrapper:not(.active) .thumbnail:hover {
  opacity: 0.7;
}

.thumbnail-wrapper.active {
  border: 2px solid hsl(26, 100%, 55%);
}

.thumbnail-wrapper.active .thumbnail {
  opacity: 0.5;
}

.product-title {
  font-size: 44px;
  font-weight: 700;
  margin-bottom: 20px;
}

.product-description {
  color: hsl(219, 9%, 45%);
  line-height: 1.6;
  margin-bottom: 30px;
}

.price-info {
  margin-bottom: 30px;
}

.price-row {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.current-price {
  font-size: 28px;
  font-weight: 700;
  color: #000000;
  margin-right: 15px;
}

.discount {
  background-color: hsl(25, 100%, 94%);
  color: hsl(26, 100%, 55%);
  padding: 4px 8px;
  border-radius: 6px;
  font-weight: 700;
}

.original-price {
  text-decoration: line-through;
  color: hsl(220, 14%, 75%);
  font-size: 16px;
}

.add-to-cart-container {
  display: flex;
  align-items: center;
  gap: 15px;
}

.quantity-selector {
  display: flex;
  align-items: center;
  background-color: hsl(223, 64%, 98%);
  border-radius: 10px;
  overflow: hidden;
}

.quantity-selector button, .quantity-selector span {
  padding: 15px 20px;
  border: none;
  background: none;
  font-size: 16px;
  font-weight: 700;
}

.quantity-selector button {
  color: hsl(26, 100%, 55%);
  cursor: pointer;
}

.add-to-cart-button {
  background-color: hsl(26, 100%, 55%);
  color: white;
  border: none;
  padding: 15px 30px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-grow: 1;
  box-shadow: 0 20px 30px -10px hsl(26, 100%, 55%, 0.3);
}

.add-to-cart-button i {
  margin-right: 10px;
}

.add-to-cart-button svg {
  margin-right: 16px;
}

@media (max-width: 768px) {
  .product-page {
    flex-direction: column;
    padding: 20px;
  }
  
  .image-gallery {
    position: relative;
    width: 100vw;
    margin-left: -20px;
    margin-right: -20px;
  }

  .gallery-nav {
    display: flex;
  }

  .company-name {
    display: block;
  }

  .product-info {
    width: 100%;
  }

  .main-image {
    width: 100%;
    border-radius: 0;
  }

  .thumbnail-container {
    display: none;
  }

  .add-to-cart-container {
    flex-direction: column;
    width: 100%;
    margin-top: 20px;
  }

  .quantity-selector, .add-to-cart-button {
    width: 100%;
  }

  .quantity-selector {
    justify-content: space-between;
    padding: 2px 10px;
    font-size: 18px;
  }

  .quantity-selector button, .quantity-selector span {
    padding: 10px 20px;
  }

  .quantity-selector button {
    font-size: 20px;
    padding: 10px;
  }

  .add-to-cart-button {
    margin-top: 10px;
  }

  .product-title {
    font-size: 32px;
    text-align: left;
  }

  .product-description {
    text-align: left;
    margin-bottom: 20px;
  }

  .price-info {
    text-align: center;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
  }

  .price-row {
    justify-content: center;
  }

  .original-price {
    margin-top: 10px;
  }

}

</style>
