"use client"

import React from "react"

import type { ReactElement } from "react"

import { useState } from "react"
import Image from "next/image"
import Link from "next/link"
import {
  ArrowLeft,
  Camera,
  Heart,
  Mail,
  MapPin,
  Phone,
  Star,
  Upload,
  Utensils,
  X,
  Menu,
  MessageSquare,
  ContactIcon,
  Share2,
} from "lucide-react"
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb"

import { restaurantData } from "@/lib/restaurant-data"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Separator } from "@/components/ui/separator"
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Dialog, DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"
import { Label } from "@/components/ui/label"

/* ---------- helpers ---------- */
const getRestaurantById = (id: string) => restaurantData[id as keyof typeof restaurantData]

const getSimilarRestaurants = (current: any) => {
  const others = Object.values(restaurantData).filter((r) => r.id !== current.id)
  /** Same-cuisine -> same city -> rating */
  return others
    .sort((a, b) => {
      const cuisineScore = Number(b.cuisine === current.cuisine) - Number(a.cuisine === current.cuisine)
      if (cuisineScore !== 0) return cuisineScore

      const cityScore = Number(b.location.includes("Miami")) - Number(a.location.includes("Miami"))
      if (cityScore !== 0) return cityScore

      return b.rating - a.rating
    })
    .slice(0, 6)
}

const getRestaurantFAQs = (restaurant: any) => [
  {
    question: "What are the restaurant's hours?",
    answer: Object.entries(restaurant.hours)
      .map(([day, hours]) => `${day}: ${hours}`)
      .join(", "),
  },
  {
    question: "Do you take reservations?",
    answer:
      "Yes, we recommend making reservations especially for dinner service. You can call us or book online through our website.",
  },
  {
    question: "What type of cuisine do you serve?",
    answer: `We specialize in ${restaurant.cuisine} cuisine with authentic flavors and fresh ingredients.`,
  },
  {
    question: "Do you offer delivery or takeout?",
    answer:
      restaurant.features.includes("Delivery") || restaurant.features.includes("Takeout")
        ? "Yes, we offer both delivery and takeout options. Please call us to place your order."
        : "We currently offer dine-in service only. Please visit us to enjoy the full restaurant experience.",
  },
  {
    question: "Is parking available?",
    answer: restaurant.features.includes("Valet Parking")
      ? "Yes, we offer valet parking for your convenience."
      : "Street parking is available nearby. We recommend checking local parking regulations.",
  },
  {
    question: "Do you accommodate dietary restrictions?",
    answer:
      restaurant.features.includes("Vegetarian Options") || restaurant.features.includes("Vegan Options")
        ? "Yes, we offer vegetarian and vegan options. Please inform your server about any dietary restrictions."
        : "We can accommodate most dietary restrictions with advance notice. Please call ahead to discuss your needs.",
  },
  {
    question: "What is your cancellation policy?",
    answer: "We require 24-hour notice for cancellations. Same-day cancellations may be subject to a fee.",
  },
  {
    question: "Do you have private dining options?",
    answer:
      restaurant.features.includes("Private Dining") || restaurant.features.includes("Private Events")
        ? "Yes, we offer private dining rooms for special events and group dining. Please contact us for availability and pricing."
        : "We can accommodate small groups but don't have dedicated private dining rooms. Please call to discuss your needs.",
  },
]

const getBreadcrumbSchema = (restaurant: any) => ({
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  itemListElement: [
    {
      "@type": "ListItem",
      position: 1,
      name: "United States",
      item: "https://foodiehub.com/us",
    },
    {
      "@type": "ListItem",
      position: 2,
      name: restaurant.cuisine,
      item: `https://foodiehub.com/category/${restaurant.cuisine.toLowerCase()}`,
    },
    {
      "@type": "ListItem",
      position: 3,
      name: restaurant.name,
      item: `https://foodiehub.com/restaurant/${restaurant.id}`,
    },
  ],
})

const getFAQSchema = (faqs: any[]) => ({
  "@context": "https://schema.org",
  "@type": "FAQPage",
  mainEntity: faqs.map((faq) => ({
    "@type": "Question",
    name: faq.question,
    acceptedAnswer: {
      "@type": "Answer",
      text: faq.answer,
    },
  })),
})

/* ---------- page ---------- */
export default function RestaurantMenuPage({
  params,
}: {
  params: { id: string }
}): ReactElement {
  const restaurant = getRestaurantById(params.id)
  const [selectedImage, setSelectedImage] = useState<string | null>(null)
  const [showReviewForm, setShowReviewForm] = useState(false)
  const [reviewRating, setReviewRating] = useState(0)
  const [reviewImages, setReviewImages] = useState<string[]>([])
  const [activeSection, setActiveSection] = useState("menu")
  const [showPhoneNumber, setShowPhoneNumber] = useState(false)
  const [showMap, setShowMap] = useState(false)

  const featureIcons: Record<string, any> = {
    "Outdoor Seating": Utensils,
    "Wine Bar": Utensils,
    "Private Dining": Utensils,
    Takeout: Utensils,
    Delivery: Utensils,
    "Valet Parking": Utensils,
    "Live Music": Utensils,
    "Romantic Atmosphere": Heart,
  }

  const allGalleryImages = [...restaurant.images, ...restaurant.customerReviews.flatMap((r) => r.images || [])]

  const onUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const files = e.target.files
    if (!files) return
    const urls = Array.from(files).map((file) => URL.createObjectURL(file))
    setReviewImages((prev) => [...prev, ...urls])
  }
  const removeImage = (idx: number) => setReviewImages((prev) => prev.filter((_, i) => i !== idx))

  const scrollToSection = (sectionId: string) => {
    const element = document.getElementById(sectionId)
    if (element) {
      const headerHeight = 120 // Account for header + breadcrumb height
      const elementPosition = element.offsetTop - headerHeight
      window.scrollTo({
        top: elementPosition,
        behavior: "smooth",
      })
      setActiveSection(sectionId)

      // Special handling for contact section
      if (sectionId === "contact") {
        // Scroll to the Restaurant Info card specifically
        setTimeout(() => {
          const contactCard = document.querySelector("#contact")
          if (contactCard) {
            contactCard.scrollIntoView({ behavior: "smooth", block: "start" })
          }
        }, 100)
      }
    }
  }

  // Track scroll position to update active section
  const handleScroll = () => {
    const sections = ["menu", "photos", "reviews", "contact"]
    const scrollPosition = window.scrollY + 200

    for (const section of sections) {
      const element = document.getElementById(section)
      if (element && scrollPosition >= element.offsetTop) {
        setActiveSection(section)
      }
    }
  }

  React.useEffect(() => {
    window.addEventListener("scroll", handleScroll)
    return () => window.removeEventListener("scroll", handleScroll)
  }, [])

  if (!restaurant) return <div>Restaurant not found</div>

  /** Share restaurant URL with graceful fallback */
  const handleShare = async () => {
    const shareData = {
      title: restaurant.name,
      text: `Check out ${restaurant.name} – ${restaurant.description}`,
      url: window.location.href,
    }

    if (navigator.share) {
      try {
        await navigator.share(shareData)
      } catch (err: any) {
        // If the user cancels (`AbortError`) we silently ignore.
        // For any other error (e.g. permission denied) fall back to clipboard.
        if (err?.name !== "AbortError") {
          await navigator.clipboard.writeText(shareData.url)
          alert("Link copied to clipboard!")
        }
      }
    } else {
      await navigator.clipboard.writeText(shareData.url)
      alert("Link copied to clipboard!")
    }
  }

  /* ---------- JSX ---------- */
  return (
    <div className="min-h-screen flex flex-col bg-background">
      {/* ---------- header ---------- */}
      <header className="border-b bg-white sticky top-0 z-50">
        <div className="container mx-auto px-4 py-4 flex items-center justify-between">
          <Link href="/" className="flex items-center gap-2">
            <ArrowLeft className="w-5 h-5" />
            <span className="font-medium">Back to Restaurants</span>
          </Link>
          <Button variant="outline" size="sm" className="bg-transparent" onClick={handleShare}>
            <Share2 className="w-4 h-4 mr-1" />
            Share
          </Button>
        </div>
      </header>

      {/* Schema markup */}
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(getBreadcrumbSchema(restaurant)) }}
      />
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(getFAQSchema(getRestaurantFAQs(restaurant))) }}
      />

      {/* Breadcrumb Navigation */}
      <div className="border-b bg-gray-50">
        <div className="container mx-auto px-4 py-3">
          <Breadcrumb>
            <BreadcrumbList>
              <BreadcrumbItem>
                <BreadcrumbLink href="/">United States</BreadcrumbLink>
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbLink href={`/category/${restaurant.cuisine.toLowerCase()}`}>
                  {restaurant.cuisine}
                </BreadcrumbLink>
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbPage>{restaurant.name}</BreadcrumbPage>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>
        </div>
      </div>

      {/* ---------- main ---------- */}
      <main className="flex-1">
        <section className="container mx-auto px-4 py-8 md:py-12">
          <div className="grid lg:grid-cols-3 gap-8">
            {/* ---------- left column ---------- */}
            <div className="lg:col-span-2 space-y-8">
              {/* info */}
              <div>
                <h1 className="text-3xl font-bold">{restaurant.name}</h1>
                <div className="flex items-center gap-2 text-muted-foreground mt-1">
                  <MapPin className="w-4 h-4" />
                  <span>{restaurant.location}</span>
                </div>
                <div className="flex items-center gap-4 mt-2">
                  <div className="flex items-center gap-1">
                    <Star className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                    <span className="font-semibold">{restaurant.rating}</span>
                    <span className="text-sm text-muted-foreground">({restaurant.reviews})</span>
                  </div>
                  <Badge variant="secondary">{restaurant.cuisine}</Badge>
                  <Badge variant="outline">{restaurant.priceRange}</Badge>
                </div>
                <p className="text-muted-foreground mt-4">{restaurant.description}</p>
              </div>

              <Separator />

              {/* special offer */}
              {restaurant.specialOffer && (
                <div className="bg-orange-50 border border-orange-200 rounded-lg p-4">
                  <h3 className="font-semibold text-orange-800 mb-1">Special Offer</h3>
                  <p className="text-orange-700">{restaurant.specialOffer}</p>
                </div>
              )}

              {/* ---------- menu ---------- */}
              <div id="menu">
                <h2 className="text-2xl font-bold mb-4">Menu</h2>
                <Tabs defaultValue={Object.keys(restaurant.menu)[0]}>
                  <TabsList className="grid md:grid-cols-6 grid-cols-3">
                    {Object.keys(restaurant.menu).map((key) => (
                      <TabsTrigger key={key} value={key}>
                        {key.charAt(0).toUpperCase() + key.slice(1)}
                      </TabsTrigger>
                    ))}
                  </TabsList>

                  {Object.entries(restaurant.menu).map(([category, items]) => (
                    <TabsContent key={category} value={category}>
                      <div className="space-y-4 mt-6">
                        {items.map((item: any, i: number) => (
                          <Card key={i} className="md:flex-row flex-col flex hover:shadow-md transition-shadow">
                            {item.image && (
                              <Image
                                src={item.image || "/placeholder.svg"}
                                width={128}
                                height={128}
                                alt={item.name}
                                className="object-cover w-full md:w-32 h-32 md:h-auto"
                                onClick={() => setSelectedImage(item.image)}
                              />
                            )}
                            <CardContent className="flex-1 p-4">
                              <div className="flex justify-between gap-4">
                                <div className="flex-1">
                                  <h3 className="font-semibold">
                                    {item.name}{" "}
                                    {item.popular && (
                                      <Badge className="ml-2 bg-red-500 hover:bg-red-600 text-xs">Popular</Badge>
                                    )}
                                  </h3>
                                  <p className="text-muted-foreground text-sm">{item.description}</p>
                                </div>
                                <div className="text-right whitespace-nowrap">
                                  <div className="font-bold">${item.price}</div>
                                </div>
                              </div>
                            </CardContent>
                          </Card>
                        ))}
                      </div>
                    </TabsContent>
                  ))}
                </Tabs>
              </div>

              <Separator />

              {/* features */}
              <div>
                <h2 className="text-2xl font-bold mb-4">Features</h2>
                <div className="grid md:grid-cols-2 gap-3">
                  {restaurant.features.map((f) => {
                    const Icon = featureIcons[f] || Utensils
                    return (
                      <div key={f} className="flex items-center gap-2 bg-muted p-3 rounded-lg">
                        <Icon className="w-4 h-4 text-primary" />
                        <span>{f}</span>
                      </div>
                    )
                  })}
                </div>
              </div>

              <Separator />

              {/* gallery */}
              <div id="photos">
                <h2 className="text-2xl font-bold mb-4">Photo Gallery</h2>
                <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                  {allGalleryImages.slice(0, 12).map((src, i) => (
                    <Image
                      key={i}
                      src={src || "/placeholder.svg"}
                      width={200}
                      height={150}
                      alt={`gallery-${i}`}
                      className="object-cover w-full h-32 rounded-lg cursor-pointer hover:opacity-80"
                      onClick={() => setSelectedImage(src)}
                    />
                  ))}
                </div>
              </div>

              {/* Map Section */}
              {showMap && (
                <div className="mt-8">
                  <h3 className="text-xl font-bold mb-4">Location & Directions</h3>
                  <div className="bg-muted rounded-lg p-4 mb-4">
                    <div className="flex items-center gap-2 mb-2">
                      <MapPin className="w-4 h-4 text-primary" />
                      <span className="font-medium">{restaurant.address}</span>
                    </div>
                    <p className="text-sm text-muted-foreground">{restaurant.location}</p>
                  </div>
                  <div className="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                    <iframe
                      src={`https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=${encodeURIComponent(restaurant.address)}`}
                      width="100%"
                      height="100%"
                      style={{ border: 0 }}
                      allowFullScreen
                      loading="lazy"
                      referrerPolicy="no-referrer-when-downgrade"
                      className="rounded-lg"
                      title={`Map showing location of ${restaurant.name}`}
                    />
                  </div>
                  <div className="mt-4 flex gap-2">
                    <Button
                      variant="outline"
                      className="flex-1 bg-transparent"
                      onClick={() =>
                        window.open(
                          `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(restaurant.address)}`,
                          "_blank",
                        )
                      }
                    >
                      Get Directions
                    </Button>
                    <Button variant="outline" className="flex-1 bg-transparent" onClick={() => setShowMap(false)}>
                      Hide Map
                    </Button>
                  </div>
                </div>
              )}

              <Separator />

              {/* reviews */}
              <div id="reviews">
                <div className="flex justify-between items-center mb-4">
                  <h2 className="text-2xl font-bold">Reviews</h2>
                  <Button onClick={() => setShowReviewForm(true)}>Write a review</Button>
                </div>

                {/* summary */}
                <div className="bg-muted p-6 rounded-lg mb-6 flex flex-col md:flex-row gap-6">
                  <div className="text-center md:w-1/3">
                    <div className="text-4xl font-bold">{restaurant.rating}</div>
                    <div className="flex justify-center my-1">
                      {Array.from({ length: 5 }).map((_, i) => (
                        <Star
                          key={i}
                          className={`w-5 h-5 ${
                            i + 1 <= Math.round(restaurant.rating) ? "fill-yellow-400 text-yellow-400" : "text-gray-300"
                          }`}
                        />
                      ))}
                    </div>
                    <p className="text-muted-foreground text-sm">Based on {restaurant.reviews} reviews</p>
                  </div>
                  {/* simple progress bars – placeholder logic */}
                  <div className="flex-1 space-y-2">
                    {[5, 4, 3, 2, 1].map((r) => {
                      const pct = Math.floor(Math.random() * 40) + 20
                      return (
                        <div key={r} className="flex items-center gap-2">
                          <span className="w-8 text-sm">{r}★</span>
                          <div className="flex-1 h-2 bg-gray-200 rounded">
                            <div className="h-2 bg-yellow-400 rounded" style={{ width: `${pct}%` }} />
                          </div>
                          <span className="w-10 text-sm text-right">{pct}%</span>
                        </div>
                      )
                    })}
                  </div>
                </div>

                {/* individual reviews */}
                <div className="space-y-6">
                  {restaurant.customerReviews.map((r) => (
                    <Card key={r.id}>
                      <CardContent className="p-6 space-y-2">
                        <div className="flex items-center gap-3 mb-2">
                          <div className="w-10 h-10 flex items-center justify-center rounded-full bg-primary text-white font-semibold">
                            {r.name[0]}
                          </div>
                          <div>
                            <h4 className="font-medium">{r.name}</h4>
                            <div className="flex items-center gap-1">
                              {Array.from({ length: 5 }).map((_, i) => (
                                <Star
                                  key={i}
                                  className={`w-4 h-4 ${
                                    i + 1 <= r.rating ? "fill-yellow-400 text-yellow-400" : "text-gray-300"
                                  }`}
                                />
                              ))}
                              <span className="text-xs text-muted-foreground ml-2">
                                {new Date(r.date).toLocaleDateString()}
                              </span>
                            </div>
                          </div>
                        </div>
                        <p className="text-muted-foreground">{r.comment}</p>
                        {r.images?.length > 0 && (
                          <div className="grid grid-cols-2 gap-2">
                            {r.images.map((src, i) => (
                              <Image
                                key={i}
                                src={src || "/placeholder.svg"}
                                width={150}
                                height={100}
                                alt={`review-${i}`}
                                className="object-cover w-full h-20 rounded cursor-pointer"
                                onClick={() => setSelectedImage(src)}
                              />
                            ))}
                          </div>
                        )}
                        <Button variant="ghost" size="sm" className="text-muted-foreground mt-2">
                          <Heart className="w-4 h-4 mr-1" />
                          Helpful ({r.helpful})
                        </Button>
                      </CardContent>
                    </Card>
                  ))}
                </div>
              </div>

              <Separator />

              {/* FAQs */}
              <div>
                <h2 className="text-2xl font-bold mb-6">Frequently Asked Questions</h2>
                <div className="space-y-4">
                  {getRestaurantFAQs(restaurant).map((faq, index) => (
                    <Card key={index}>
                      <CardContent className="p-6">
                        <h3 className="font-semibold text-lg mb-3">{faq.question}</h3>
                        <p className="text-muted-foreground leading-relaxed">{faq.answer}</p>
                      </CardContent>
                    </Card>
                  ))}
                </div>
              </div>
            </div>

            {/* ---------- sidebar ---------- */}
            <div className="space-y-6 sticky top-24 h-max">
              {/* info card */}
              <Card id="contact">
                <CardHeader>
                  <CardTitle>Restaurant Info</CardTitle>
                </CardHeader>
                <CardContent className="space-y-6">
                  <div>
                    <h4 className="font-semibold mb-2">Contact</h4>
                    <div className="space-y-2 text-sm">
                      <div className="flex items-center gap-2">
                        <Phone className="w-4 h-4 text-primary" />
                        {restaurant.phone}
                      </div>
                      <div className="flex items-center gap-2">
                        <Mail className="w-4 h-4 text-primary" />
                        {restaurant.email}
                      </div>
                      <div className="flex items-center gap-2">
                        <MapPin className="w-4 h-4 text-primary" />
                        {restaurant.address}
                      </div>
                    </div>
                  </div>

                  <Separator />

                  <div>
                    <h4 className="font-semibold mb-3">Opening Hours</h4>
                    <div className="space-y-2 text-sm">
                      {Object.entries(restaurant.hours).map(([day, hours]) => (
                        <div key={day} className="flex justify-between items-center">
                          <span className="text-muted-foreground min-w-[80px]">{day}</span>
                          <span className="font-medium">{hours}</span>
                        </div>
                      ))}
                    </div>
                  </div>

                  <Separator />

                  <div className="space-y-2">
                    <Button className="w-full" onClick={() => setShowPhoneNumber(!showPhoneNumber)}>
                      <Phone className="w-4 h-4 mr-1" />
                      Reserve
                    </Button>
                    {showPhoneNumber && (
                      <div className="bg-muted p-3 rounded-lg text-center">
                        <p className="text-sm text-muted-foreground mb-2">Call to make a reservation:</p>
                        <a href={`tel:${restaurant.phone}`} className="text-lg font-semibold text-primary">
                          {restaurant.phone}
                        </a>
                      </div>
                    )}
                    <Button variant="outline" className="w-full bg-transparent" onClick={() => setShowMap(!showMap)}>
                      <MapPin className="w-4 h-4 mr-1" />
                      Directions
                    </Button>
                  </div>
                </CardContent>
              </Card>

              {/* similar restaurants */}
              <Card>
                <CardHeader>
                  <CardTitle className="flex items-center gap-2">
                    <Utensils className="w-5 h-5" />
                    Similar Restaurants
                  </CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                  {getSimilarRestaurants(restaurant).map((r) => (
                    <Link
                      key={r.id}
                      href={`/restaurant/${r.id}`}
                      className="flex gap-3 p-2 rounded hover:bg-muted transition-colors"
                    >
                      <Image
                        src={r.image || "/placeholder.svg"}
                        width={64}
                        height={64}
                        alt={r.name}
                        className="rounded-md object-cover w-16 h-16 flex-shrink-0"
                      />
                      <div className="flex-1 min-w-0">
                        <h4 className="font-semibold text-sm truncate">{r.name}</h4>
                        <div className="flex items-center gap-1 text-xs mt-1">
                          <Star className="w-3 h-3 fill-yellow-400 text-yellow-400" />
                          {r.rating} <span className="text-muted-foreground">({r.reviews})</span>
                        </div>
                        <div className="flex justify-between items-center mt-1">
                          <Badge variant="secondary" className="text-xs">
                            {r.cuisine}
                          </Badge>
                          <span className="text-xs text-muted-foreground">{r.priceRange}</span>
                        </div>
                      </div>
                    </Link>
                  ))}
                  <Separator className="my-3" />
                  <Link href="/">
                    <Button variant="outline" className="w-full bg-transparent">
                      View All
                    </Button>
                  </Link>
                </CardContent>
              </Card>
            </div>
          </div>
        </section>
      </main>

      {/* Mobile Bottom Navigation */}
      <div className="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 md:hidden z-40">
        <div className="grid grid-cols-4 h-16">
          <button
            onClick={() => scrollToSection("menu")}
            className={`flex flex-col items-center justify-center space-y-1 ${
              activeSection === "menu" ? "text-primary" : "text-muted-foreground"
            }`}
          >
            <Menu className="w-5 h-5" />
            <span className="text-xs font-medium">Menus</span>
          </button>
          <button
            onClick={() => scrollToSection("photos")}
            className={`flex flex-col items-center justify-center space-y-1 ${
              activeSection === "photos" ? "text-primary" : "text-muted-foreground"
            }`}
          >
            <Camera className="w-5 h-5" />
            <span className="text-xs font-medium">Photos</span>
          </button>
          <button
            onClick={() => scrollToSection("reviews")}
            className={`flex flex-col items-center justify-center space-y-1 ${
              activeSection === "reviews" ? "text-primary" : "text-muted-foreground"
            }`}
          >
            <MessageSquare className="w-5 h-5" />
            <span className="text-xs font-medium">Reviews</span>
          </button>
          <button
            onClick={() => scrollToSection("contact")}
            className={`flex flex-col items-center justify-center space-y-1 ${
              activeSection === "contact" ? "text-primary" : "text-muted-foreground"
            }`}
          >
            <ContactIcon className="w-5 h-5" />
            <span className="text-xs font-medium">Contact</span>
          </button>
        </div>
      </div>

      {/* Add bottom padding for mobile to account for fixed navigation */}
      <div className="h-16 md:hidden"></div>

      {/* ---------- footer ---------- */}
      <footer className="bg-muted py-8 mt-12">
        <div className="container mx-auto px-4 text-center text-sm text-muted-foreground">
          <p>&copy; 2024 FoodieHub. All rights reserved.</p>
        </div>
      </footer>

      {/* ---------- image modal ---------- */}
      {selectedImage && (
        <Dialog open={!!selectedImage} onOpenChange={() => setSelectedImage(null)}>
          <DialogContent className="max-w-4xl">
            <Image
              src={selectedImage || "/placeholder.svg"}
              alt="selected"
              width={1200}
              height={800}
              className="w-full h-auto rounded-lg"
            />
          </DialogContent>
        </Dialog>
      )}

      {/* ---------- review modal ---------- */}
      {showReviewForm && (
        <Dialog open={showReviewForm} onOpenChange={setShowReviewForm}>
          <DialogContent className="max-w-2xl">
            <DialogHeader>
              <DialogTitle>Write a Review</DialogTitle>
            </DialogHeader>
            <div className="space-y-5">
              <div>
                <Label>Rating</Label>
                <div className="flex gap-1 mt-1">
                  {Array.from({ length: 5 }).map((_, i) => (
                    <Star
                      key={i}
                      className={`w-6 h-6 cursor-pointer ${
                        i + 1 <= reviewRating ? "fill-yellow-400 text-yellow-400" : "text-gray-300"
                      }`}
                      onClick={() => setReviewRating(i + 1)}
                    />
                  ))}
                </div>
              </div>

              <Input placeholder="Your name" />
              <Textarea placeholder="Share your experience..." className="min-h-[120px]" />

              <div>
                <Label>Add photos (optional)</Label>
                <div className="border-2 border-dashed rounded-lg p-6 text-center mt-2">
                  <input id="upload" type="file" multiple accept="image/*" className="hidden" onChange={onUpload} />
                  <label htmlFor="upload" className="cursor-pointer">
                    <Upload className="w-8 h-8 mx-auto text-muted-foreground" />
                    <p className="text-sm">Click to upload</p>
                  </label>
                </div>
                {reviewImages.length > 0 && (
                  <div className="grid grid-cols-3 gap-2 mt-4">
                    {reviewImages.map((src, i) => (
                      <div key={i} className="relative">
                        <Image
                          src={src || "/placeholder.svg"}
                          width={100}
                          height={100}
                          alt="preview"
                          className="object-cover w-full h-20 rounded"
                        />
                        <Button
                          size="sm"
                          variant="destructive"
                          className="absolute -top-2 -right-2 w-6 h-6 p-0"
                          onClick={() => removeImage(i)}
                        >
                          <X className="w-3 h-3" />
                        </Button>
                      </div>
                    ))}
                  </div>
                )}
              </div>

              <div className="flex justify-end gap-2">
                <Button variant="outline" onClick={() => setShowReviewForm(false)}>
                  Cancel
                </Button>
                <Button>Submit</Button>
              </div>
            </div>
          </DialogContent>
        </Dialog>
      )}
    </div>
  )
}
