"use client"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { Input } from "@/components/ui/input"
import { Checkbox } from "@/components/ui/checkbox"
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from "@/components/ui/sheet"
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog"
import { Star, MapPin, Phone, Clock, Utensils, Car, CreditCard, Search, Filter, ArrowLeft, Menu, X } from "lucide-react"
import Image from "next/image"
import Link from "next/link"

// Restaurant data organized by category
const restaurantsByCategory = {
  italian: [
    {
      id: 1,
      name: "Bella Vista Italian",
      location: "Downtown Miami, FL",
      rating: 4.8,
      reviews: 1247,
      image: "/placeholder.svg?height=400&width=600&text=Bella+Vista",
      description: "Authentic Italian cuisine with fresh pasta made daily and wood-fired pizzas in a cozy atmosphere.",
      phone: "+1 (305) 555-0123",
      cuisine: "Italian",
      priceRange: "$$-$$$",
      hours: "11:00 AM - 10:00 PM",
      specialOffer: "Happy Hour: 3-6 PM - 50% off appetizers!",
      features: ["Outdoor Seating", "Wine Bar", "Private Dining", "Takeout", "Delivery"],
      popularDishes: ["Margherita Pizza", "Fettuccine Alfredo", "Tiramisu"],
      isOpen: true,
      hasDelivery: true,
    },
    {
      id: 9,
      name: "La Trattoria",
      location: "South Beach, Miami, FL",
      rating: 4.9,
      reviews: 1089,
      image: "/placeholder.svg?height=400&width=600&text=La+Trattoria",
      description:
        "Traditional Italian family recipes passed down through generations, featuring homemade pasta and authentic sauces.",
      phone: "+1 (305) 555-0999",
      cuisine: "Italian",
      priceRange: "$$$-$$$$",
      hours: "5:00 PM - 11:00 PM",
      specialOffer: "Wine pairing dinner every Thursday",
      features: ["Wine Cellar", "Romantic Setting", "Chef's Table", "Valet Parking"],
      popularDishes: ["Osso Buco", "Risotto Milanese", "Cannoli"],
      isOpen: false,
      hasDelivery: false,
    },
  ],
  japanese: [
    {
      id: 2,
      name: "Sakura Sushi House",
      location: "Brickell, Miami, FL",
      rating: 4.9,
      reviews: 892,
      image: "/placeholder.svg?height=400&width=600&text=Sakura+Sushi",
      description: "Fresh sushi and traditional Japanese dishes prepared by master chefs with premium ingredients.",
      phone: "+1 (305) 555-0456",
      cuisine: "Japanese",
      priceRange: "$$$-$$$$",
      hours: "5:00 PM - 11:00 PM",
      specialOffer: "Omakase special: Chef's choice 8-course tasting menu",
      features: ["Sushi Bar", "Private Rooms", "Sake Selection", "Fresh Fish Daily"],
      popularDishes: ["Dragon Roll", "Chirashi Bowl", "Miso Black Cod"],
      isOpen: false,
      hasDelivery: false,
    },
    {
      id: 10,
      name: "Tokyo Bay",
      location: "Downtown Miami, FL",
      rating: 4.5,
      reviews: 634,
      image: "/placeholder.svg?height=400&width=600&text=Tokyo+Bay",
      description: "Modern Japanese cuisine with a fusion twist, featuring innovative rolls and traditional dishes.",
      phone: "+1 (305) 555-0777",
      cuisine: "Japanese",
      priceRange: "$$$",
      hours: "12:00 PM - 10:00 PM",
      specialOffer: "Lunch bento boxes starting at $15",
      features: ["Modern Decor", "Lunch Specials", "Sake Bar", "Takeout"],
      popularDishes: ["Rainbow Roll", "Teriyaki Salmon", "Mochi Ice Cream"],
      isOpen: true,
      hasDelivery: true,
    },
  ],
  american: [
    {
      id: 3,
      name: "The Burger Joint",
      location: "South Beach, Miami, FL",
      rating: 4.6,
      reviews: 2156,
      image: "/placeholder.svg?height=400&width=600&text=Burger+Joint",
      description: "Gourmet burgers made with locally sourced beef and creative toppings in a casual setting.",
      phone: "+1 (305) 555-0789",
      cuisine: "American",
      priceRange: "$-$$",
      hours: "11:00 AM - 12:00 AM",
      specialOffer: "Buy 2 burgers, get 1 free milkshake - Weekdays only!",
      features: ["Outdoor Seating", "Sports Bar", "Kids Menu", "Craft Beer", "Vegan Options"],
      popularDishes: ["Classic Cheeseburger", "Truffle Fries", "Chocolate Shake"],
      isOpen: true,
      hasDelivery: true,
    },
  ],
  indian: [
    {
      id: 4,
      name: "Spice Garden Indian",
      location: "Coral Gables, FL",
      rating: 4.7,
      reviews: 743,
      image: "/placeholder.svg?height=400&width=600&text=Spice+Garden",
      description: "Authentic Indian flavors with traditional spices and modern presentation in an elegant setting.",
      phone: "+1 (305) 555-0321",
      cuisine: "Indian",
      priceRange: "$$-$$$",
      hours: "12:00 PM - 10:00 PM",
      specialOffer: "Lunch buffet: All-you-can-eat for $16.99 (Mon-Fri)",
      features: ["Lunch Buffet", "Vegetarian Options", "Catering", "Private Events"],
      popularDishes: ["Butter Chicken", "Biryani", "Garlic Naan"],
      isOpen: true,
      hasDelivery: true,
    },
  ],
  seafood: [
    {
      id: 5,
      name: "Ocean Breeze Seafood",
      location: "Key Biscayne, FL",
      rating: 4.9,
      reviews: 1534,
      image: "/placeholder.svg?height=400&width=600&text=Ocean+Breeze",
      description: "Fresh seafood with ocean views, featuring daily catches and signature lobster dishes.",
      phone: "+1 (305) 555-0987",
      cuisine: "Seafood",
      priceRange: "$$$-$$$$",
      hours: "4:00 PM - 11:00 PM",
      specialOffer: "Fresh catch of the day - Market price",
      features: ["Ocean View", "Fresh Daily Catch", "Wine Pairing", "Romantic Setting"],
      popularDishes: ["Lobster Thermidor", "Grilled Mahi", "Seafood Paella"],
      isOpen: false,
      hasDelivery: false,
    },
  ],
  mexican: [
    {
      id: 6,
      name: "Taco Libre Mexican",
      location: "Wynwood, Miami, FL",
      rating: 4.5,
      reviews: 967,
      image: "/placeholder.svg?height=400&width=600&text=Taco+Libre",
      description: "Vibrant Mexican street food with authentic flavors and creative cocktails in artistic Wynwood.",
      phone: "+1 (305) 555-0654",
      cuisine: "Mexican",
      priceRange: "$-$$",
      hours: "11:00 AM - 2:00 AM",
      specialOffer: "Taco Tuesday: $2 tacos all day!",
      features: ["Street Art Murals", "Craft Cocktails", "Live Music", "Late Night"],
      popularDishes: ["Fish Tacos", "Carnitas", "Margaritas"],
      isOpen: true,
      hasDelivery: true,
    },
  ],
}

const cuisineCategories = [
  { name: "All Restaurants", slug: "all", count: 6, icon: Utensils },
  { name: "Italian", slug: "italian", count: 2, icon: Utensils },
  { name: "Japanese", slug: "japanese", count: 2, icon: Utensils },
  { name: "American", slug: "american", count: 1, icon: Utensils },
  { name: "Indian", slug: "indian", count: 1, icon: Utensils },
  { name: "Seafood", slug: "seafood", count: 1, icon: Utensils },
  { name: "Mexican", slug: "mexican", count: 1, icon: Utensils },
]

const categoryInfo = {
  italian: {
    title: "Italian Restaurants",
    description:
      "Discover authentic Italian cuisine with fresh pasta, wood-fired pizzas, and traditional recipes passed down through generations.",
    image: "/placeholder.svg?height=300&width=800&text=Italian+Cuisine",
  },
  japanese: {
    title: "Japanese Restaurants",
    description:
      "Experience the art of Japanese cuisine with fresh sushi, traditional dishes, and modern fusion creations.",
    image: "/placeholder.svg?height=300&width=800&text=Japanese+Cuisine",
  },
  american: {
    title: "American Restaurants",
    description: "Enjoy classic American comfort food, gourmet burgers, and innovative dishes in a casual atmosphere.",
    image: "/placeholder.svg?height=300&width=800&text=American+Cuisine",
  },
  indian: {
    title: "Indian Restaurants",
    description:
      "Savor the rich flavors and aromatic spices of authentic Indian cuisine with vegetarian and non-vegetarian options.",
    image: "/placeholder.svg?height=300&width=800&text=Indian+Cuisine",
  },
  seafood: {
    title: "Seafood Restaurants",
    description: "Fresh catches of the day, ocean views, and expertly prepared seafood dishes from local waters.",
    image: "/placeholder.svg?height=300&width=800&text=Seafood+Cuisine",
  },
  mexican: {
    title: "Mexican Restaurants",
    description:
      "Vibrant Mexican flavors with street food favorites, craft cocktails, and authentic regional specialties.",
    image: "/placeholder.svg?height=300&width=800&text=Mexican+Cuisine",
  },
}

const featureIcons = {
  "Outdoor Seating": Utensils,
  "Wine Bar": Utensils,
  "Private Dining": Utensils,
  Takeout: Car,
  Delivery: Car,
  "Sports Bar": Utensils,
  "Kids Menu": Utensils,
  "Craft Beer": Utensils,
  "Vegan Options": Utensils,
  "Lunch Buffet": Utensils,
  "Vegetarian Options": Utensils,
  Catering: Utensils,
  "Private Events": Utensils,
  "Sushi Bar": Utensils,
  "Private Rooms": Utensils,
  "Sake Selection": Utensils,
  "Fresh Fish Daily": Utensils,
  "Ocean View": Utensils,
  "Fresh Daily Catch": Utensils,
  "Wine Pairing": Utensils,
  "Romantic Setting": Utensils,
  "Street Art Murals": Utensils,
  "Craft Cocktails": Utensils,
  "Live Music": Utensils,
  "Late Night": Utensils,
  "Wine Cellar": Utensils,
  "Chef's Table": Utensils,
  "Valet Parking": Car,
  "Modern Decor": Utensils,
  "Lunch Specials": Utensils,
  "Sake Bar": Utensils,
}

export default function CategoryPage({ params }: { params: { slug: string } }) {
  const { slug } = params
  const allRestaurants = restaurantsByCategory[slug as keyof typeof restaurantsByCategory] || []
  const category = categoryInfo[slug as keyof typeof categoryInfo]

  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [searchQuery, setSearchQuery] = useState("")
  const [sortBy, setSortBy] = useState("rating")
  const [filters, setFilters] = useState({
    openNow: false,
    topRated: false,
    hasDelivery: false,
    priceRanges: [] as string[],
  })
  const [showFilters, setShowFilters] = useState(false)

  if (!category) {
    return <div>Category not found</div>
  }

  // Filter and sort restaurants
  const filteredRestaurants = allRestaurants
    .filter((restaurant) => {
      // Search filter
      if (
        searchQuery &&
        !restaurant.name.toLowerCase().includes(searchQuery.toLowerCase()) &&
        !restaurant.popularDishes.some((dish) => dish.toLowerCase().includes(searchQuery.toLowerCase()))
      ) {
        return false
      }

      // Open now filter
      if (filters.openNow && !restaurant.isOpen) return false

      // Top rated filter (4.5+ rating)
      if (filters.topRated && restaurant.rating < 4.5) return false

      // Delivery filter
      if (filters.hasDelivery && !restaurant.hasDelivery) return false

      // Price range filter
      if (filters.priceRanges.length > 0 && !filters.priceRanges.includes(restaurant.priceRange)) return false

      return true
    })
    .sort((a, b) => {
      switch (sortBy) {
        case "rating":
          return b.rating - a.rating
        case "reviews":
          return b.reviews - a.reviews
        case "name":
          return a.name.localeCompare(b.name)
        default:
          return 0
      }
    })

  const handlePriceRangeChange = (priceRange: string, checked: boolean) => {
    setFilters((prev) => ({
      ...prev,
      priceRanges: checked ? [...prev.priceRanges, priceRange] : prev.priceRanges.filter((p) => p !== priceRange),
    }))
  }

  const clearFilters = () => {
    setFilters({
      openNow: false,
      topRated: false,
      hasDelivery: false,
      priceRanges: [],
    })
    setSearchQuery("")
  }

  return (
    <div className="min-h-screen bg-background">
      {/* Header */}
      <header className="border-b bg-white sticky top-0 z-50">
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-4">
              <Sheet open={sidebarOpen} onOpenChange={setSidebarOpen}>
                <SheetTrigger asChild>
                  <Button variant="ghost" size="sm" className="lg:hidden">
                    <Menu className="w-5 h-5" />
                  </Button>
                </SheetTrigger>
                <SheetContent side="left" className="w-80">
                  <SheetHeader>
                    <SheetTitle>Categories</SheetTitle>
                  </SheetHeader>
                  <nav className="space-y-2 mt-6">
                    {cuisineCategories.map((category) => {
                      const IconComponent = category.icon
                      const isActive = category.slug === slug
                      return (
                        <Link
                          key={category.slug}
                          href={category.slug === "all" ? "/" : `/category/${category.slug}`}
                          className={`flex items-center justify-between p-3 rounded-lg transition-colors group ${
                            isActive ? "bg-primary text-white" : "hover:bg-muted"
                          }`}
                          onClick={() => setSidebarOpen(false)}
                        >
                          <div className="flex items-center space-x-3">
                            <IconComponent
                              className={`w-5 h-5 ${
                                isActive ? "text-white" : "text-muted-foreground group-hover:text-primary"
                              }`}
                            />
                            <span className={isActive ? "text-white" : "group-hover:text-primary"}>
                              {category.name}
                            </span>
                          </div>
                          <Badge variant={isActive ? "secondary" : "secondary"} className="text-xs">
                            {category.count}
                          </Badge>
                        </Link>
                      )
                    })}
                  </nav>
                </SheetContent>
              </Sheet>
              <h1 className="text-2xl font-bold text-primary">FoodieHub</h1>
            </div>
            <nav className="hidden md:flex space-x-6">
              <Link href="/#restaurants" className="text-muted-foreground hover:text-primary">
                Restaurants
              </Link>
              <Link href="#about" className="text-muted-foreground hover:text-primary">
                About
              </Link>
              <Link href="#contact" className="text-muted-foreground hover:text-primary">
                Contact
              </Link>
            </nav>
            <Button variant="outline">Sign In</Button>
          </div>
        </div>
      </header>

      <div>
        {/* Main Content */}
        <main>
          {/* Category Hero Section */}
          <section className="relative bg-gradient-to-r from-orange-600 to-red-600 text-white py-16">
            <div className="container mx-auto px-4">
              <div className="flex items-center mb-4">
                <Link href="/" className="flex items-center space-x-2 text-white hover:text-gray-200">
                  <ArrowLeft className="w-5 h-5" />
                  <span>Back to All Restaurants</span>
                </Link>
              </div>

              <h1 className="text-4xl md:text-5xl font-bold mb-4">{category.title}</h1>
              <p className="text-xl mb-8 max-w-3xl">{category.description}</p>

              {/* Search Bar */}
              <div className="max-w-2xl mb-6">
                <div className="relative">
                  <Search className="absolute left-4 top-1/2 transform -translate-y-1/2 text-muted-foreground w-5 h-5" />
                  <Input
                    placeholder={`Search ${category.title.toLowerCase()}...`}
                    className="pl-12 pr-4 py-3 bg-white text-black border-0 rounded-full shadow-lg"
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                  />
                  <Button className="absolute right-2 top-1/2 transform -translate-y-1/2 rounded-full">Search</Button>
                </div>
              </div>
            </div>
          </section>

          {/* Restaurants Section */}
          <section className="py-16">
            <div className="container mx-auto px-4">
              <div className="flex items-center justify-between mb-8">
                <h2 className="text-3xl font-bold">
                  {filteredRestaurants.length} {category.title} Found
                </h2>
                <div className="flex items-center space-x-4">
                  <Dialog open={showFilters} onOpenChange={setShowFilters}>
                    <DialogTrigger asChild>
                      <Button variant="outline" size="sm">
                        <Filter className="w-4 h-4 mr-2" />
                        Filters
                        {filters.priceRanges.length > 0 && (
                          <Badge variant="secondary" className="ml-2 text-xs">
                            {filters.priceRanges.length}
                          </Badge>
                        )}
                      </Button>
                    </DialogTrigger>
                    <DialogContent className="max-w-md">
                      <DialogHeader>
                        <DialogTitle>Filter {category.title}</DialogTitle>
                      </DialogHeader>
                      <div className="space-y-6">
                        {/* Quick Filters */}
                        <div>
                          <h4 className="font-semibold mb-3">Quick Filters</h4>
                          <div className="space-y-3">
                            <div className="flex items-center space-x-2">
                              <Checkbox
                                id="openNow"
                                checked={filters.openNow}
                                onCheckedChange={(checked) => setFilters((prev) => ({ ...prev, openNow: !!checked }))}
                              />
                              <label htmlFor="openNow" className="text-sm">
                                Open Now
                              </label>
                            </div>
                            <div className="flex items-center space-x-2">
                              <Checkbox
                                id="topRated"
                                checked={filters.topRated}
                                onCheckedChange={(checked) => setFilters((prev) => ({ ...prev, topRated: !!checked }))}
                              />
                              <label htmlFor="topRated" className="text-sm">
                                Top Rated (4.5+)
                              </label>
                            </div>
                            <div className="flex items-center space-x-2">
                              <Checkbox
                                id="hasDelivery"
                                checked={filters.hasDelivery}
                                onCheckedChange={(checked) =>
                                  setFilters((prev) => ({ ...prev, hasDelivery: !!checked }))
                                }
                              />
                              <label htmlFor="hasDelivery" className="text-sm">
                                Delivery Available
                              </label>
                            </div>
                          </div>
                        </div>

                        {/* Price Range */}
                        <div>
                          <h4 className="font-semibold mb-3">Price Range</h4>
                          <div className="space-y-3">
                            {["$", "$-$$", "$$-$$$", "$$$-$$$$"].map((range) => (
                              <div key={range} className="flex items-center space-x-2">
                                <Checkbox
                                  id={`price-${range}`}
                                  checked={filters.priceRanges.includes(range)}
                                  onCheckedChange={(checked) => handlePriceRangeChange(range, !!checked)}
                                />
                                <label htmlFor={`price-${range}`} className="text-sm">
                                  {range}
                                </label>
                              </div>
                            ))}
                          </div>
                        </div>

                        <div className="flex space-x-3">
                          <Button variant="outline" onClick={clearFilters} className="flex-1 bg-transparent">
                            Clear All
                          </Button>
                          <Button onClick={() => setShowFilters(false)} className="flex-1">
                            Apply Filters
                          </Button>
                        </div>
                      </div>
                    </DialogContent>
                  </Dialog>
                  <select
                    className="px-3 py-2 border rounded-lg text-sm"
                    value={sortBy}
                    onChange={(e) => setSortBy(e.target.value)}
                  >
                    <option value="rating">Sort by Rating</option>
                    <option value="reviews">Sort by Reviews</option>
                    <option value="name">Sort by Name</option>
                  </select>
                </div>
              </div>

              {/* Active Filters Display */}
              {(searchQuery ||
                filters.openNow ||
                filters.topRated ||
                filters.hasDelivery ||
                filters.priceRanges.length > 0) && (
                <div className="mb-6 flex flex-wrap gap-2">
                  {searchQuery && (
                    <Badge variant="secondary" className="flex items-center gap-1">
                      Search: "{searchQuery}"
                      <X className="w-3 h-3 cursor-pointer" onClick={() => setSearchQuery("")} />
                    </Badge>
                  )}
                  {filters.openNow && (
                    <Badge variant="secondary" className="flex items-center gap-1">
                      Open Now
                      <X
                        className="w-3 h-3 cursor-pointer"
                        onClick={() => setFilters((prev) => ({ ...prev, openNow: false }))}
                      />
                    </Badge>
                  )}
                  {filters.topRated && (
                    <Badge variant="secondary" className="flex items-center gap-1">
                      Top Rated
                      <X
                        className="w-3 h-3 cursor-pointer"
                        onClick={() => setFilters((prev) => ({ ...prev, topRated: false }))}
                      />
                    </Badge>
                  )}
                  {filters.hasDelivery && (
                    <Badge variant="secondary" className="flex items-center gap-1">
                      Delivery
                      <X
                        className="w-3 h-3 cursor-pointer"
                        onClick={() => setFilters((prev) => ({ ...prev, hasDelivery: false }))}
                      />
                    </Badge>
                  )}
                  {filters.priceRanges.map((range) => (
                    <Badge key={range} variant="secondary" className="flex items-center gap-1">
                      {range}
                      <X className="w-3 h-3 cursor-pointer" onClick={() => handlePriceRangeChange(range, false)} />
                    </Badge>
                  ))}
                  <Button variant="ghost" size="sm" onClick={clearFilters}>
                    Clear All
                  </Button>
                </div>
              )}

              {filteredRestaurants.length === 0 ? (
                <div className="text-center py-16">
                  <Utensils className="w-16 h-16 mx-auto text-muted-foreground mb-4" />
                  <h3 className="text-xl font-semibold mb-2">No restaurants found</h3>
                  <p className="text-muted-foreground mb-6">
                    Try adjusting your search or filters to find more restaurants.
                  </p>
                  <Button onClick={clearFilters}>Clear All Filters</Button>
                </div>
              ) : (
                <div className="grid md:grid-cols-2 lg:grid-cols-2 gap-8">
                  {filteredRestaurants.map((restaurant) => (
                    <Card key={restaurant.id} className="overflow-hidden hover:shadow-lg transition-shadow">
                      <div className="relative h-64">
                        <Image
                          src={restaurant.image || "/placeholder.svg"}
                          alt={restaurant.name}
                          fill
                          className="object-cover"
                        />
                        {restaurant.specialOffer && (
                          <Badge className="absolute top-4 left-4 bg-red-500 hover:bg-red-600">Special Offer</Badge>
                        )}
                        <div className="absolute top-4 right-4 flex gap-2">
                          <Badge variant="secondary" className="bg-white/90 text-black">
                            {restaurant.cuisine}
                          </Badge>
                          {restaurant.isOpen && <Badge className="bg-green-500 hover:bg-green-600">Open</Badge>}
                        </div>
                      </div>

                      <CardContent className="p-6">
                        <CardHeader className="p-0 mb-4">
                          <div className="flex items-start justify-between mb-2">
                            <CardTitle className="text-xl">{restaurant.name}</CardTitle>
                            <div className="flex items-center space-x-1">
                              <Star className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                              <span className="font-semibold">{restaurant.rating}</span>
                              <span className="text-muted-foreground text-sm">({restaurant.reviews})</span>
                            </div>
                          </div>
                          <div className="flex items-center text-muted-foreground mb-2">
                            <MapPin className="w-4 h-4 mr-1" />
                            {restaurant.location}
                          </div>
                          <p className="text-muted-foreground text-sm">{restaurant.description}</p>
                        </CardHeader>

                        <div className="space-y-4">
                          {/* Restaurant Info */}
                          <div className="flex items-center justify-between text-sm">
                            <div className="flex items-center space-x-4">
                              <div className="flex items-center">
                                <Clock className="w-4 h-4 mr-1 text-primary" />
                                <span>{restaurant.hours}</span>
                              </div>
                              <div className="flex items-center">
                                <CreditCard className="w-4 h-4 mr-1 text-primary" />
                                <span>{restaurant.priceRange}</span>
                              </div>
                            </div>
                          </div>

                          {/* Popular Dishes */}
                          <div>
                            <h4 className="font-semibold mb-2 text-sm">Popular Dishes:</h4>
                            <div className="flex flex-wrap gap-1">
                              {restaurant.popularDishes.map((dish, index) => (
                                <Badge key={index} variant="outline" className="text-xs">
                                  {dish}
                                </Badge>
                              ))}
                            </div>
                          </div>

                          {/* Features */}
                          <div>
                            <h4 className="font-semibold mb-2 text-sm">Features:</h4>
                            <div className="grid grid-cols-2 gap-1">
                              {restaurant.features.slice(0, 4).map((feature, index) => {
                                const IconComponent = featureIcons[feature as keyof typeof featureIcons] || Utensils
                                return (
                                  <div key={index} className="flex items-center space-x-1">
                                    <IconComponent className="w-3 h-3 text-primary" />
                                    <span className="text-xs">{feature}</span>
                                  </div>
                                )
                              })}
                            </div>
                          </div>

                          {/* Special Offer */}
                          {restaurant.specialOffer && (
                            <div className="bg-orange-50 border border-orange-200 rounded-lg p-3">
                              <p className="text-orange-700 font-medium text-sm">{restaurant.specialOffer}</p>
                            </div>
                          )}

                          {/* Action Buttons */}
                          <div className="flex space-x-3 pt-2">
                            <Link href={`/restaurant/${restaurant.id}`} className="flex-1">
                              <Button className="w-full">View Menu</Button>
                            </Link>
                            <Button variant="outline" className="flex-1 bg-transparent">
                              <Phone className="w-4 h-4 mr-1" />
                              Call
                            </Button>
                          </div>
                        </div>
                      </CardContent>
                    </Card>
                  ))}
                </div>
              )}

              {/* Load More Button */}
              {filteredRestaurants.length > 0 && (
                <div className="text-center mt-12">
                  <Button variant="outline" size="lg" className="px-8 bg-transparent">
                    Load More {category.title}
                  </Button>
                </div>
              )}
            </div>
          </section>
        </main>
      </div>

      {/* Footer */}
      <footer className="bg-muted py-12">
        <div className="container mx-auto px-4">
          <div className="grid md:grid-cols-4 gap-8">
            <div>
              <h3 className="font-bold text-lg mb-4">FoodieHub</h3>
              <p className="text-muted-foreground">
                Your trusted partner for discovering the best restaurants and dining experiences.
              </p>
            </div>
            <div>
              <h4 className="font-semibold mb-4">Quick Links</h4>
              <ul className="space-y-2 text-muted-foreground">
                <li>
                  <Link href="#" className="hover:text-primary">
                    About Us
                  </Link>
                </li>
                <li>
                  <Link href="#" className="hover:text-primary">
                    Contact
                  </Link>
                </li>
                <li>
                  <Link href="#" className="hover:text-primary">
                    Privacy Policy
                  </Link>
                </li>
                <li>
                  <Link href="#" className="hover:text-primary">
                    Terms of Service
                  </Link>
                </li>
              </ul>
            </div>
            <div>
              <h4 className="font-semibold mb-4">For Restaurants</h4>
              <ul className="space-y-2 text-muted-foreground">
                <li>
                  <Link href="#" className="hover:text-primary">
                    List Your Restaurant
                  </Link>
                </li>
                <li>
                  <Link href="#" className="hover:text-primary">
                    Business Dashboard
                  </Link>
                </li>
                <li>
                  <Link href="#" className="hover:text-primary">
                    Marketing Tools
                  </Link>
                </li>
                <li>
                  <Link href="#" className="hover:text-primary">
                    Support
                  </Link>
                </li>
              </ul>
            </div>
            <div>
              <h4 className="font-semibold mb-4">Contact</h4>
              <div className="space-y-2 text-muted-foreground">
                <p>1-800-FOODIE</p>
                <p>support@foodiehub.com</p>
                <p>Available 24/7</p>
              </div>
            </div>
          </div>
          <div className="border-t mt-8 pt-8 text-center text-muted-foreground">
            <p>&copy; 2024 FoodieHub. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  )
}
