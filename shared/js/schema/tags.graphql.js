export default `
extend type Query {
    tags: [Tag!]! @all(model: "Gernzy\Server\Models\Tag")
    tag(id: Int! @eq): Tag @find(model: "Gernzy\Server\Models\Tag")
}

extend type Mutation {
    createTag(input: CreateTagInput!): Tag
        @field(resolver: "Gernzy\Server\GraphQL\Mutations\Tag@create")
        @can(ability: "create", model: "Gernzy\Server\Models\Tag", policy: "Gernzy\Server\Policies\TagPolicy")
    updateTag(id: ID!, input: UpdateTagInput!): Tag
        @field(resolver: "Gernzy\Server\GraphQL\Mutations\Tag@update")
        @can(ability: "update", model: "Gernzy\Server\Models\Tag", policy: "Gernzy\Server\Policies\TagPolicy")
    deleteTag(id: ID!): DeleteResult
        @field(resolver: "Gernzy\Server\GraphQL\Mutations\Tag@delete")
        @can(ability: "delete", model: "Gernzy\Server\Models\Tag", policy: "Gernzy\Server\Policies\TagPolicy")
}

type Tag {
    id: ID!
    name: String!
    products: [Product!]! @hasMany(type: "paginator")
}

input CreateTagInput {
    name: String!
}

input UpdateTagInput {
    name: String!
} 
`
